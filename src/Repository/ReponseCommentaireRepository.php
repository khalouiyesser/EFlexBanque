<?php

namespace App\Repository;

use App\Entity\ReponseCommentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReponseCommentaire>
 *
 * @method ReponseCommentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseCommentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseCommentaire[]    findAll()
 * @method ReponseCommentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseCommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseCommentaire::class);
    }

//    /**
//     * @return ReponseCommentaire[] Returns an array of ReponseCommentaire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReponseCommentaire
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findById($id){
    return $this->createQueryBuilder('r')
                ->andWhere('r.user = :val')
                ->setParameter('val', $id)
                ->getQuery()
                ->getOneOrNullResult()
           ;  
}

public function findReponseByReclamation($offreId)
{
    return $this->createQueryBuilder('b')
        ->join('b.commentaireHadhemi', 'a')
        ->where('a.id = :commentaire')
        ->setParameter('commentaire', $offreId)
        ->getQuery()
        ->getResult();
}


}
