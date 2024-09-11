<?php

namespace App\Repository;

use App\Entity\OffreStage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OffreStage>
 *
 * @method OffreStage|null find($id, $lockMode = null, $lockVersion = null)
 * @method OffreStage|null findOneBy(array $criteria, array $orderBy = null)
 * @method OffreStage[]    findAll()
 * @method OffreStage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreStageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OffreStage::class);
    }

    /**
     * @return OffreStage[] Returns an array of OffreStage objects
     */
    public function findDemandeByOffre($value): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.demande = :val')
            ->setParameter('val', $value)
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return OffreStage[] Returns an array of OffreStage objects
     */
    public function findOneBySomeField($value): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.domaine = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

}
