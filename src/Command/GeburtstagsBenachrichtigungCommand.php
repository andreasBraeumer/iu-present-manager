<?php

namespace App\Command;

use App\Entity\Benachrichtigung;
use App\Entity\Geschenk;
use App\Enum\GeschenkStatus;
use App\Repository\BenachrichtigungRepository;
use App\Repository\PersonRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:benachrichtigung:geburtstage',
    description: 'Erzeugt Benachrichtigungen für Personen mit Geburtstag im nächsten Monat.',
)]
class GeburtstagsBenachrichtigungCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly PersonRepository $personRepository,
        private readonly BenachrichtigungRepository $benachrichtigungRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $heute = new \DateTimeImmutable('today');

        if (1 !== (int) $heute->format('j')) {
            $io->note('Läuft nur am 1. eines Monats – keine Meldungen erzeugt.');

            return Command::SUCCESS;
        }

        $erzeugt = 0;
        foreach ($this->userRepository->findAll() as $user) {
            foreach ($this->personRepository->findGeburtstageImNaechstenMonat($user) as $person) {
                if ($this->benachrichtigungRepository->existsForPersonAndTag($person, 'geburtstag_naechster_monat', $heute)) {
                    continue;
                }

                $ideen = array_filter(
                    $person->getGeschenke()->toArray(),
                    static fn (Geschenk $geschenk) => GeschenkStatus::Verschenkt !== $geschenk->getStatus(),
                );
                $titel = array_map(static fn (Geschenk $geschenk) => $geschenk->getTitel(), $ideen);

                $inhalt = [] === $titel
                    ? \sprintf('Geburtstag am %s, noch keine Geschenkidee hinterlegt.', $person->getGeburtsdatum()->format('d.m.'))
                    : \sprintf('Geburtstag am %s, bekannte Ideen: %s.', $person->getGeburtsdatum()->format('d.m.'), implode(', ', $titel));

                $benachrichtigung = new Benachrichtigung();
                $benachrichtigung->setPerson($person);
                $benachrichtigung->setTyp('geburtstag_naechster_monat');
                $benachrichtigung->setInhalt($inhalt);
                $benachrichtigung->setGeplantAm($heute);
                $benachrichtigung->setGesendetAm($heute);
                $benachrichtigung->setGelesen(false);

                $this->entityManager->persist($benachrichtigung);
                ++$erzeugt;
            }
        }

        $this->entityManager->flush();

        $io->success(\sprintf('%d Geburtstags-Benachrichtigung(en) erzeugt.', $erzeugt));

        return Command::SUCCESS;
    }
}
