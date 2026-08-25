<?php

namespace App\Repository;

use App\Entity\Benachrichtigung;
use App\Entity\Person;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Benachrichtigung>
 */
class BenachrichtigungRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Benachrichtigung::class);
    }

    public function existsForPersonAndTag(Person $person, string $typ, \DateTimeImmutable $tag): bool
    {
        $anzahl = $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->andWhere('b.person = :person')
            ->andWhere('b.typ = :typ')
            ->andWhere('b.geplant_am >= :von')
            ->andWhere('b.geplant_am < :bis')
            ->setParameter('person', $person)
            ->setParameter('typ', $typ)
            ->setParameter('von', $tag->setTime(0, 0))
            ->setParameter('bis', $tag->modify('+1 day')->setTime(0, 0))
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return $anzahl > 0;
    }

    /**
     * @return Benachrichtigung[]
     */
    public function findUngelesenForUser(User $user): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.person', 'p')
            ->andWhere('p.user = :user')
            ->andWhere('b.gelesen = false')
            ->setParameter('user', $user)
            ->orderBy('b.geplant_am', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
