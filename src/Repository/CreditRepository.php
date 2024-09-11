<?php

namespace App\Repository;

use App\Entity\Credit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Credit>
 *
 * @method Credit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Credit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Credit[]    findAll()
 * @method Credit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreditRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Credit::class);
    }

    public function searchcreditminmax($min, $max)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT p FROM App\Entity\Credit p WHERE p.montant >= :min AND p.montant <= :max')
                    ->setParameter('min', $min)
                    ->setParameter('max', $max);
        return $query->getResult();
    }

    public function findAllSortedByMontant(): array
    {
        return $this->createQueryBuilder('credit')
            ->orderBy('credit.montant', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findmontant()
    {
        return $this->createQueryBuilder('a')
            ->setMaxResults(3) // Limite à 3 articles
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Credit[] Returns an array of Credit objects
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

//    public function findOneBySomeField($value): ?Credit
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
