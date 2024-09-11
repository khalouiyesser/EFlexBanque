<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Demandestage;
class DemandeStageFixtures extends Fixture implements FixtureGroupInterface
{
    
    public function load(ObjectManager $manager): void
    {
        for ( $i =0; $i<20 ; $i++){
        $demande = new Demandestage();
        $demande->setEmail("khaluiyesser@gmail.com");
       // $demande->setOffreStage(1);
        $demande ->setNumerotelephone(25114365 );
        $demande ->setNom("khaloui");
        $demande ->setPrenom("yesser");
        $demande ->setLettremotivation("Cher(e) recruteur/recruteuse,

                    Je vous adresse la présente lettre de motivation dans l’espoir de postuler à l’offre d’emploi [nom du poste] que vous avez récemment publiée. Avec [X] années d’expérience dans le domaine [préciser le domaine d'expertise] et un fort intérêt pour [mentionner un aspect particulier de l'entreprise ou de l'offre d'emploi], je pense avoir les compétences et la passion nécessaires pour contribuer à votre équipe.
                    
                    J'ai eu l'occasion de travailler dans divers contextes professionnels, allant de [mentionner une expérience particulière pertinente] à [mentionner une autre expérience pertinente]. Ces expériences m'ont permis de développer mes compétences en [mentionner les compétences pertinentes pour le poste] et d'affiner ma capacité à [mentionner une autre compétence pertinente]. En particulier, je me suis spécialisé(e) dans [mentionner un domaine d'expertise pertinent pour le poste].");
        $demande->setDomaine("Informatique");
        $demande->setCv("5e9f8fa9-6a2a-4a62-87db-17322506ffef.pdf");
        
        $manager->persist($demande);
        $manager->flush();
        }
        
    }
    public static function getGroups(): array
    {
        return ['DemandeStage'];
    }
}
