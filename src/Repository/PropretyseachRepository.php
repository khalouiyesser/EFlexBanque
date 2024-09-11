<?php

namespace App\Repository;

use App\Entity\Propretyseach;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Propretyseach>
 *
 * @method Propretyseach|null find($id, $lockMode = null, $lockVersion = null)
 * @method Propretyseach|null findOneBy(array $criteria, array $orderBy = null)
 * @method Propretyseach[]    findAll()
 * @method Propretyseach[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropretyseachRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Propretyseach::class);
    }

//    /**
//     * @return Propretyseach[] Returns an array of Propretyseach objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Propretyseach
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
