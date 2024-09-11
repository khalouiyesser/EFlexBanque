<?php

namespace App\Repository;

use App\Entity\Demandestage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Demandestage>
 *
 * @method Demandestage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demandestage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demandestage[]    findAll()
 * @method Demandestage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeStageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demandestage::class);
    }

    /**
     * @return Demandestage[] Returns an array of Demandestage objects
     */
    public function findByEtat($value): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.etat = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Demandestage
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    
    public function findDemandesByOffre($offreId)
    {
        return $this->createQueryBuilder('b')
            ->join('b.offreStage', 'a')
            ->where('a.id = :offreId')
            ->setParameter('offreId', $offreId)
            ->getQuery()
            ->getResult();
    }
  
    public function Recherche($id){
        return $this->createQueryBuilder('B')
            ->where('B.numerotelephone LIKE :numerotelephone')
            ->setParameter('numerotelephone',$id)
            ->getQuery()
            ->getResult();
    }
}
