<?php

namespace App\Repository;

use App\Entity\Virement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Twilio\Rest\Client;

/**
 * @extends ServiceEntityRepository<Virement>
 *
 * @method Virement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Virement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Virement[]    findAll()
 * @method Virement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VirementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Virement::class);
    }
    
    /**
     * @return Virement[] Returns an array of Virement objects
     */
    public function listeDesVirements($value): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.DecisionV = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }
    public function listeDesVirementsAccepte($value): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.DecisionV = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }
    public function listeDesVirementsRefuse($value): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.DecisionV = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }
    
    public function sms(String $num ,String $body) : void
    {
        // Your Account SID and Auth Token from twilio.com/console
        $sid = 'AC2dc3c96e5eaecb94c5425ab65f689f6e';
        $auth_token = '97895badcf378978038558e7d50dbc6f';
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
        // A Twilio number you own with SMS capabilities
        $twilio_number = "+13395007652";
        
        $client = new Client($sid, $auth_token);
        $client->messages->create(
        // the number you'd like to send the message to
            $num,
            [
                // A Twilio phone number you purchased at twilio.com/console
                'from' => $twilio_number,
                // the body of the text message you'd like to send
                'body' => $body
            ]
        );
    }

    public function getNombreVirementsParType($user, $typeVirement): int
    {
        return $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->andWhere('v.user = :user')
            ->andWhere('v.TypeVirement = :type')
            ->setParameter('user', $user)
            ->setParameter('type', $typeVirement )
            ->getQuery()
            ->getSingleScalarResult();
    }




}