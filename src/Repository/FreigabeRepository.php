<?php

namespace App\Repository;

use App\Entity\Freigabe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Freigabe>
 */
class FreigabeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Freigabe::class);
    }

    public function findValidByToken(string $token): ?Freigabe
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.token = :token')
            ->andWhere('f.ablauf_am IS NULL OR f.ablauf_am > :jetzt')
            ->setParameter('token', $token)
            ->setParameter('jetzt', new \DateTimeImmutable())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
