<?php

namespace App\Repository;

use App\Entity\Reponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reponse>
 *
 * @method Reponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reponse[]    findAll()
 * @method Reponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reponse::class);
    }

   /**
   * @return Reponse[] Returns an array of Reponse objects
   */


    public function findOneBySomeField($value): ?Reponse
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
 
    
   
    public function findByExampleField($value): array
    {
       return $this->createQueryBuilder('r')
            ->andWhere('r.user = :val')
            ->setParameter('val', $value)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    /**
   * @return Reponse[] Returns an array of Reponse objects
   */
    public function findReponseByReclamationAndUser($reclamation, $user): ?Reponse
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.reclamation = :reclamation')
        ->andWhere('r.user = :user')
        ->setParameter('reclamation', $reclamation)
        ->setParameter('user', $user)
        ->getQuery()
        ->getOneOrNullResult()
    ;
}
public function findReponseByReclamation($offreId)
{
    return $this->createQueryBuilder('b')
        ->join('b.reclamation', 'a')
        ->where('a.id = :reclamation')
        ->setParameter('reclamation', $offreId)
        ->getQuery()
        ->getResult();
}



}
