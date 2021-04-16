<?php

namespace App\Repository;

use App\Entity\SiteChecks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SiteChecks|null find($id, $lockMode = null, $lockVersion = null)
 * @method SiteChecks|null findOneBy(array $criteria, array $orderBy = null)
 * @method SiteChecks[]    findAll()
 * @method SiteChecks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SiteChecksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SiteChecks::class);
    }

    // /**
    //  * @return SiteChecks[] Returns an array of SiteChecks objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SiteChecks
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
