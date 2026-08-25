<?php

namespace App\Command;

use App\Entity\Anlass;
use App\Entity\Benachrichtigung;
use App\Entity\Geschenk;
use App\Entity\Person;
use App\Enum\GeschenkStatus;
use App\Repository\BenachrichtigungRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Funktion 9 (Weihnachts-Statusmeldungen): erzeugt einmal täglich zwischen dem 1. und 25. Dezember je
 * Person mit mindestens einem Weihnachts-Geschenk eine Benachrichtigung mit dem aktuellen Planungsstand.
 *
 * Gedacht für einen täglichen Cron-Aufruf im Dezember auf dem Server, siehe CLAUDE.md.
 */
#[AsCommand(
    name: 'app:benachrichtigung:weihnachtsstatus',
    description: 'Erzeugt Weihnachts-Statusmeldungen für alle Personen mit geplanten Weihnachtsgeschenken.',
)]
class WeihnachtsStatusCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BenachrichtigungRepository $benachrichtigungRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $heute = new \DateTimeImmutable('today');

        if ($heute->format('n') != 12 || (int) $heute->format('j') > 25) {
            $io->note('Außerhalb des Weihnachts-Zeitraums (1.–25. Dezember) – keine Meldungen erzeugt.');

            return Command::SUCCESS;
        }

        $weihnachten = $this->entityManager->getRepository(Anlass::class)->findOneBy(['bezeichnung' => 'Weihnachten']);

        if (null === $weihnachten) {
            $io->note('Kein Anlass "Weihnachten" gefunden – keine Meldungen erzeugt.');

            return Command::SUCCESS;
        }

        /** @var array<int, array{person: Person, geschenke: Geschenk[]}> $nachPerson */
        $nachPerson = [];
        foreach ($weihnachten->getGeschenke() as $geschenk) {
            $person = $geschenk->getPerson();
            if (null === $person) {
                continue;
            }
            $nachPerson[$person->getId()]['person'] = $person;
            $nachPerson[$person->getId()]['geschenke'][] = $geschenk;
        }

        $erzeugt = 0;
        foreach ($nachPerson as ['person' => $person, 'geschenke' => $geschenke]) {
            if ($this->benachrichtigungRepository->existsForPersonAndTag($person, 'weihnachten_status', $heute)) {
                continue;
            }

            $anzahlNachStatus = [
                GeschenkStatus::Idee->value => 0,
                GeschenkStatus::Geplant->value => 0,
                GeschenkStatus::Besorgt->value => 0,
                GeschenkStatus::Verschenkt->value => 0,
            ];
            foreach ($geschenke as $geschenk) {
                ++$anzahlNachStatus[$geschenk->getStatus()->value];
            }

            $inhalt = \sprintf(
                '%d Idee(n), %d geplant, %d besorgt, %d verschenkt für Weihnachten.',
                $anzahlNachStatus[GeschenkStatus::Idee->value],
                $anzahlNachStatus[GeschenkStatus::Geplant->value],
                $anzahlNachStatus[GeschenkStatus::Besorgt->value],
                $anzahlNachStatus[GeschenkStatus::Verschenkt->value],
            );

            $benachrichtigung = new Benachrichtigung();
            $benachrichtigung->setPerson($person);
            $benachrichtigung->setTyp('weihnachten_status');
            $benachrichtigung->setInhalt($inhalt);
            $benachrichtigung->setGeplantAm($heute);
            $benachrichtigung->setGesendetAm($heute);
            $benachrichtigung->setGelesen(false);

            $this->entityManager->persist($benachrichtigung);
            ++$erzeugt;
        }

        $this->entityManager->flush();

        $io->success(\sprintf('%d Weihnachts-Statusmeldung(en) erzeugt.', $erzeugt));

        return Command::SUCCESS;
    }
}
