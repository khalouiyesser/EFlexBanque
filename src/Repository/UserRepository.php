<?php
namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private $entityManager;
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $this->getEntityManager();
    }
    
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }
        
        $user->setPassword($newHashedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
    
    public function findByRole(string $role): array
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%' . $role . '%')
            ->getQuery();
        return $query->getResult();
    }
    public function findOneByEmail(string $role): array
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.email LIKE :role')
            ->setParameter('role',  $role )
            ->getQuery();
        return $query->getResult();
    }
    public function searchByIdAndRole($id, $roles)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.id = :id')
            ->andWhere('b.roles LIKE :roles')
            ->setParameter('id', $id)
            ->setParameter('roles', '%' . $roles . '%')
            ->getQuery()
            ->getResult();
    }
    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    
    //count unBlocked users
    public function countUnBlocked()
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->andWhere('u.isBlocked = :val')
            ->setParameter('val', false)
            ->getQuery()
            ->getSingleScalarResult();
    }
//count number of users
    public function countAll()
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    //count users with role ADMIN
    public function countAdmin()
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->andWhere('u.roles LIKE :roles')
            ->setParameter('roles', '%ROLE_ADMIN%')
            ->getQuery()
            ->getSingleScalarResult();
    }


//count Blocked users
    public function countBlocked()
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->andWhere('u.isBlocked = :val')
            ->setParameter('val', true )
            ->getQuery()
            ->getSingleScalarResult();
    }
//count users with role EMPLOYE
    public function countEmploye()
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->andWhere('u.roles LIKE :roles')
            ->setParameter('roles', '%ROLE_EMPLOYE%')
            ->getQuery()
            ->getSingleScalarResult();
    }

//count users with role CLIENT
    public function countClient()
    {
        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->andWhere('u.roles LIKE :roles')
            ->setParameter('roles', '%ROLE_CLIENT%')
            ->getQuery()
            ->getSingleScalarResult();
    }
//trie
    public function findAllClientsOrderedByName()
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"ROLE_CLIENT"%')
            ->orderBy('u.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findAllEmployesOrderedByName()
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"ROLE_EMPLOYEE"%')
            ->orderBy('u.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }