<?php

namespace App\Repository;

use App\Entity\CommentaireHadhemi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommentaireHadhemi>
 *
 * @method CommentaireHadhemi|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentaireHadhemi|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentaireHadhemi[]    findAll()
 * @method CommentaireHadhemi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireHadhemiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentaireHadhemi::class);
    }

//    /**
//     * @return CommentaireHadhemi[] Returns an array of CommentaireHadhemi objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CommentaireHadhemi
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
