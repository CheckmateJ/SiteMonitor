<?php

namespace App\Repository;

use App\Entity\SiteTestResults;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SiteTestResults|null find($id, $lockMode = null, $lockVersion = null)
 * @method SiteTestResults|null findOneBy(array $criteria, array $orderBy = null)
 * @method SiteTestResults[]    findAll()
 * @method SiteTestResults[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SiteTestResultsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SiteTestResults::class);
    }

    // /**
    //  * @return SiteTestResults[] Returns an array of SiteTestResults objects
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
    public function findOneBySomeField($value): ?SiteTestResults
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
