<?php

namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class AppFixtures extends Fixture implements FixtureGroupInterface

{
    
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ){}
    public function load(ObjectManager $manager): void
    {
        
        $admin =new User();
        $admin ->setEmail('admin@gmail.com');
        $admin ->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $manager->flush();

    }
    public static function getGroups(): array
    {
        return ['user'];
    }
}