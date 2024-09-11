<?php

namespace App\Repository;

use App\Entity\BirdStripePayments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BirdStripePayments>
 *
 * @method BirdStripePayments|null find($id, $lockMode = null, $lockVersion = null)
 * @method BirdStripePayments|null findOneBy(array $criteria, array $orderBy = null)
 * @method BirdStripePayments[]    findAll()
 * @method BirdStripePayments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BirdStripePaymentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BirdStripePayments::class);
    }

//    /**
//     * @return BirdStripePayments[] Returns an array of BirdStripePayments objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BirdStripePayments
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
