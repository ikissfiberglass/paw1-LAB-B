<?php

namespace App\Repository;

use App\Entity\ForecastRecord;
use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ForecastRecord>
 */
class ForecastRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForecastRecord::class);
    }


    public function findByLocation(Location $location)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->where('m.location = :location')
            ->setParameter('location', $location)
            ->andWhere('m.timestamp > :now')
            ->setParameter('now', date('Y-m-d'));

        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result;



    }
//    /**
//     * @return ForecastRecord[] Returns an array of ForecastRecord objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ForecastRecord
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
