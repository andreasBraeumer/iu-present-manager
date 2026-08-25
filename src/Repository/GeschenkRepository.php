<?php

namespace App\Repository;

use App\Entity\Geschenk;
use App\Entity\Person;
use App\Enum\GeschenkStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Geschenk>
 */
class GeschenkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Geschenk::class);
    }

    /**
     * Vorschläge für neue Ideen: Titel bereits verschenkter Geschenke anderer Personen desselben Users,
     * die diese Person noch nicht hat.
     *
     * @return array<int, array{titel: string, beschreibung: ?string}>
     */
    public function findVorschlaege(Person $person, int $limit = 5): array
    {
        $vorhandeneTitel = array_map(
            static fn (Geschenk $geschenk) => $geschenk->getTitel(),
            $person->getGeschenke()->toArray(),
        );

        $rows = $this->createQueryBuilder('g')
            ->select('g.titel AS titel', 'g.beschreibung AS beschreibung')
            ->join('g.person', 'p')
            ->andWhere('p.user = :user')
            ->andWhere('p != :person')
            ->andWhere('g.status = :status')
            ->setParameter('user', $person->getUser())
            ->setParameter('person', $person)
            ->setParameter('status', GeschenkStatus::Verschenkt)
            ->orderBy('g.erstellt_am', 'DESC')
            ->getQuery()
            ->getArrayResult()
        ;

        $vorschlaege = [];
        foreach ($rows as $row) {
            if (\in_array($row['titel'], $vorhandeneTitel, true)) {
                continue;
            }

            if (!isset($vorschlaege[$row['titel']])) {
                $vorschlaege[$row['titel']] = [
                    'titel' => $row['titel'],
                    'beschreibung' => $row['beschreibung'],
                ];
            }

            if (\count($vorschlaege) >= $limit) {
                break;
            }
        }

        return array_values($vorschlaege);
    }
}
