<?php

namespace App\Controller;


use App\Repository\CreditRepository;

use App\Entity\Compte;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActualiteController extends AbstractController
{
    #[Route('/EFlexBanque', name: 'app_actualite')]
    public function index(): Response
    {
        return $this->render('BaseFront.html.twig', [
            'controller_name' => 'ActualiteController',
        ]);
    }
    
    #[Route('/dashbordEmploye', name: 'app_dashbordEmploye')]
    public function indexdashbordE(): Response
    {
        return $this->render('Employe/baseEmploye.html.twig', [
            'controller_name' => 'ActualiteController',
        ]);
    }
    
    #[Route('/dashbordClient', name: 'app_dashbordClient')]
    public function indexdashbordC(UserRepository $repository): Response
    {
        $compte = $this->getDoctrine()->getRepository(Compte::class)->findOneBy([]);
        
        // Assurez-vous que $compte n'est pas null et récupérez le montant total
        // $Montant = ($compte !== null) ? $compte->getMontant() : 0;
        // dd($compte);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        //$users = $compte->getId();
        //$user = $repository->find($users);
        
        
        
        return $this->render('frontOffice/Client/dashboard.html.twig', [
            'Montant' => '$Montant',
            'user' => $user,
        ]);
    }
    
    #[Route('/client', name: 'client')]
    public function client(): Response
    {
        return $this->render('employe/reclamationEmploye.html.twig', [
            'controller_name' => 'ActualiteController',
        ]);
    }
   
    
    #[Route('/clientall', name: 'clientall')]
    public function clientall(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'ActualiteController',
        ]);
    }
    
    #[Route('/afficherRecRep', name: 'app_client')]
    public function afficherRecRep(): Response
    {
        return $this->render('client.html.twig', [
            'controller_name' => 'ActualiteController',
        ]);
    }
    
    
    #[Route('/dashbordClientS', name: 'app_dashbordClientS')]
    public function indexdashbordCS(): Response
    {
        $compte = $this->getDoctrine()->getRepository(Compte::class)->findOneBy([]);
        
        // Assurez-vous que $compte n'est pas null et récupérez le type de compte
        $typeCompte = ($compte !== null) ? $compte->getTypeCompte() : '';
        
        return $this->render('frontOffice/Client/dashboard.html.twig', [
            'typeCompte' => $typeCompte,
        ]);
    }
    
    #[Route('/dashboard', name: 'app_dashboard_admin')]
    public function dashboard(CreditRepository $creditRepository): Response
    {
        $montant = $creditRepository->findMontant();
        
        return $this->render('dashboardAdmin.html.twig', [
            'montant' => $montant,
        ]);
    }
    #[Route('/Profilclient', name: 'app_ProfilClient')]
    public function indexdprofilC(): Response
    {
        return $this->render('user/profilC.html.twig', [
            'controller_name' => 'ActualiteController',
        ]);
    }
    #[Route('/ProfilAdmin', name: 'app_ProfilAdmin')]
    public function indexdprofilA(): Response
    {
        return $this->render('user/profilA.html.twig', [
            'controller_name' => 'ActualiteController',
        ]);
    }
    #[Route('/ProfilEmploye', name: 'app_ProfilEmploye')]
    public function indexdprofilE(): Response
    {
        return $this->render('user/profilE.html.twig', [
            'controller_name' => 'ActualiteController',
        ]);
    }
}
//        "symfonycasts/reset-password-bundle": "^1.14",
