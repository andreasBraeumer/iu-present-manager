<?php

namespace App\Repository;

use App\Entity\Person;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Person>
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    /**
     * @return Person[]
     */
    public function findAllForUser(User $user): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user)
            ->orderBy('p.vorname', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Personen des Users, deren Geburtstag (unabhängig vom Jahr) in den nächsten Kalendermonat fällt.
     *
     * @return Person[]
     */
    public function findGeburtstageImNaechstenMonat(User $user): array
    {
        $naechsterMonat = (int) (new \DateTimeImmutable('first day of next month'))->format('n');

        $personen = $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->andWhere('p.geburtsdatum IS NOT NULL')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;

        $gefiltert = array_values(array_filter(
            $personen,
            static fn (Person $person): bool => (int) $person->getGeburtsdatum()->format('n') === $naechsterMonat
        ));

        usort(
            $gefiltert,
            static fn (Person $a, Person $b): int => $a->getGeburtsdatum()->format('d') <=> $b->getGeburtsdatum()->format('d')
        );

        return $gefiltert;
    }
}
