<?php

namespace App\Controller;

use App\Entity\OffreStage;
use App\Form\OffreStageType;
use App\Form\SearchType;
use App\Repository\DemandeStageRepository;
use App\Repository\OffreStageRepository;
use App\Service\AnalyseCv;
use App\Service\Mailing;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffreStagesController extends AbstractController
{
    public Mailing $emailService;
    public string $directory = 'uploads_directory';
    public function __construct(Mailing $emailService)
    {
        $this->emailService = $emailService;
    }
    #[Route('/Recherche', name: 'Recherche')]
    public function Recherche(DemandeStageRepository $demandeStageRepository,Request $request): Response
    {
        $recherche = $request->get('numero');
        $demande = $demandeStageRepository->Recherche($recherche);
        return $this->render('frontOffice/demande_stage/SearchDemande.html.twig',[
            'Demandes' => $demande,
        ]);
    }
    #[Route('/RechercheDomaine', name: 'RechercheDomaine')]
    public function RechercheDomaine(OffreStageRepository $offreStageRepository,Request $request): Response
    {
        $recherche = $request->get('domaine');
        $demande = $offreStageRepository->findOneBySomeField($recherche);
        return $this->render('frontOffice/offre_stage/recrutement.html.twig',[
            'offres' => $demande,
        ]);
    }
    #[Route('/afficheOffreStages', name: 'afficheOffreStages')]
    public function afficheOffreStages(OffreStageRepository $offreStageRepository): Response
    {
        $liste = $offreStageRepository->findAll();
        return $this->render('backOffice/offre_stage/afficheOffreStages.html.twig',[
            'offres' => $liste,
            
        ]);
    }
    #[Route('/yesserA/{id}', name: 'yesserA')]
    public function yesserYesser($id,DemandeStageRepository $demandeStageRepository,AnalyseCv $cvAnalyseur,OffreStageRepository $offreStageRepository): Response
    {
        $offre = $offreStageRepository->find($id);
        $mots = $offre->getMotsCles();
        $title = $offre->getTitle();
        $listeDemande = $demandeStageRepository->findAll();
        foreach ($listeDemande as $demande) {
            $cheminFichier = $this->getParameter('uploads_directory') . '/' . $demande->getCv();
            $score = $cvAnalyseur->analyseCV($cheminFichier, $mots);
            if ($score < 100) {
                $to = $demande->getEmail();
                $nom = $demande->getNom() . " " . $demande->getPrenom();
                $subject = "Recommondation pour une offre";
                
                $html = "
<!DOCTYPE html>
<html>
<head>
    <style>
        .email-container {
            font-family: Arial, sans-serif;
            border: 1px solid #ccc;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .email-header {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .email-body {
            font-size: 16px;
            line-height: 1.5;
        }
        .email-footer {
            margin-top: 20px;
            text-align: center;
        }
        .email-footer img {
            max-width: 100px;
            display: block;
            margin: 0 auto;
        }
        .email-footer h4 {
            margin-top: 20px;
            font-size: 18px;
        }
        .email-footer p {
            font-size: 14px;
            line-height: 1.5;
        }
        .social-icons {
            list-style: none;
            padding: 0;
            margin: 10px 0;
            display: flex;
            justify-content: center;
        }
        .social-icons li {
            margin: 0 10px;
        }
        .social-icons a {
            color: #000;
            text-decoration: none;
            font-size: 20px;
        }
        .social-icons a:hover {
            color: #007bff;
        }
    </style>
    <!-- Font Awesome for social media icons -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>
</head>
<body>
    <div class='email-container'>
        <div class='email-header'>
            Bonjour {$nom}.
        </div>
        <div class='email-body'>
            Vous êtes recommandé pour l'offre {$title} sous ce chemin <a href='http://127.0.0.1:8000/DetailsOffre/{$id}'>cliquez ici</a>.
        </div>
        <div class='email-footer'>
            <img src='https://i.ibb.co/3C7DS7p/2.png' alt='pas image' />
            <h4>EFB</h4>
            <p>
                Gérer vos comptes en ligne tout en adoptant une approche écoresponsable sur le plan financier.<br><br>
                EFB vous garantit une expérience utilisateur transparente et sécurisée.
            </p>
            <ul class='social-icons'>
                <li><a rel='nofollow' href='https://www.facebook.com/profile.php?id=100087894651684' target='_blank'><i class='fab fa-facebook'></i></a></li>
                <li><a href='https://www.instagram.com/e_flex_bank/' target='_blank'><i class='fab fa-instagram'></i></a></li>
                <li><a href='mailto:eflexbank@gmail.com' target='_blank'><i class='fas fa-envelope'></i></a></li>
            </ul>
        </div>
    </div>
</body>
</html>
";

//                <a href="https://imgbb.com/"><img src="https://i.ibb.co/3C7DS7p/2.png" alt="2" border="0"></a>
                $this->emailService->sendEmail($to, $subject, $html);
            }
        }
        return $this->redirectToRoute("afficheOffreStages");
    }
    
    #[Route('/rechercheOffreStages', name: 'rechercheOffreStages')]
    public function rechercheOffreStages(OffreStageRepository $offreStageRepository,Request $request): Response
    {
        $search = [];
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);
        $domaine = $request->get('domaine');
        $liste = $offreStageRepository->findOneBySomeField($domaine);
        return $this->render('frontOffice/offre_stage/recrutement.html.twig',[
            'offres' => $liste,
            'form' => $form->createView(),
        
        ]);
    }
    
    
    #[Route('/addOffre', name: 'addOffre')]
    public function addOffre(ManagerRegistry $managerRegistry,Request $request): Response
    {
                $now = new DateTime('now');
        // Formater le temps réel actuel
       // $nowFormatted = $now->format('Y-m-d H:i:s');
////
////
////
////        // Changer le fuseau horaire à "Europe/Berlin" pendant l'été (Central European Summer Time)
        $now->setTimezone(new DateTimeZone('Europe/Berlin'));
//
//        // Réafficher le temps réel actuel
        $nowFormatted = $now->format('Y-m-d');
        
        $ajouter = "ajouter";
        $ajouterA = "ajouter avec recommandation";
        $offre = new OffreStage();
       // $cheque->setUser($this->get('security.token_storage')->getToken()->getUser());
        $offre->setUser($this->get('security.token_storage')->getToken()->getUser());
        $form = $this->createForm(OffreStageType::class,$offre);
        $form->handleRequest($request);
        $em = $managerRegistry->getManager();
        $datePostuObject = DateTime::createFromFormat('Y-m-d', $nowFormatted);
        if($form->isSubmitted() and $form->isValid() ){
            $offre ->setDatePostu($datePostuObject);
          $em->persist($offre);
          $em->flush();
          return $this->redirectToRoute('afficheOffreStages');
        }
        return $this->render('backOffice/offre_stage/add.html.twig', [
            'form' => $form->createView(),
            'ajouter' => $ajouter,
            'ajouterA' => $ajouterA
            
        ]);
    }
    #[Route('/addOffreParRecomendation', name: 'addOffreParRecomendation')]
    public function addOffreParRecomendation(ManagerRegistry $managerRegistry,Request $request): Response
    {
        $now = new DateTime('now');
        // Formater le temps réel actuel
        // $nowFormatted = $now->format('Y-m-d H:i:s');
////
////
////
////        // Changer le fuseau horaire à "Europe/Berlin" pendant l'été (Central European Summer Time)
        $now->setTimezone(new DateTimeZone('Europe/Berlin'));
//
//        // Réafficher le temps réel actuel
        $nowFormatted = $now->format('Y-m-d');
//        $listeDemande = $demandeStageRepository->findAll();
        $ajouter = "ajouter";
        $ajouterA = "ajouter avec recommandation";
        $offre = new OffreStage();
        $form = $this->createForm(OffreStageType::class,$offre);
        $form->handleRequest($request);
        $em = $managerRegistry->getManager();
        $datePostuObject = DateTime::createFromFormat('Y-m-d', $nowFormatted);
//        $mots = $form->get('motsCles')->getData();
        if($form->isSubmitted() and $form->isValid() ){
            
            $offre ->setDatePostu($datePostuObject);
            $em->persist($offre);
            //$title = $offre->getTitle();
            $em->flush();
            $id = $offre->getId();
//            $this->yesserA($id);
            return $this->redirectToRoute('yesserA',[
                'id' => $id
            ]);
        }
        return $this->render('backOffice/offre_stage/add.html.twig', [
            'form' => $form->createView(),
            'ajouter' => $ajouter,
            'ajouterA' => $ajouterA
        ]);
    }
    #[Route('/editOffre/{id}', name: 'editOffre')]
    public function editOffre($id,ManagerRegistry $managerRegistry,Request $request, OffreStageRepository $offreStageRepository): Response
    {
        $modifier = 'modifier';
        $offre = $offreStageRepository->find($id);
        $form = $this->createForm(OffreStageType::class,$offre);
        $form->handleRequest($request);
        $em = $managerRegistry->getManager();
        if($form->isSubmitted() and $form->isValid() ){
            $em->persist($offre);
            $em->flush();
            return $this->redirectToRoute('afficheOffreStages');
        }
        return $this->render('backOffice/offre_stage/add.html.twig', [
            'form' => $form->createView(),
            'ajouter' => $modifier
        ]);
    }
    #[Route('/deleteOffre/{id}', name: 'deleteOffre')]
    public function deleteOffre($id,ManagerRegistry $managerRegistry,Request $request, OffreStageRepository $offreStageRepository): Response
    {
        
        $offre = $offreStageRepository->find($id);
        
        $em = $managerRegistry->getManager();
            $em->remove($offre);
            $em->flush();
        return $this->redirectToRoute("afficheOffreStages");
    }
    #[Route('/DetailsOffre/{id}', name: 'DetailsOffre')]
    public function DetailsOffre($id,ManagerRegistry $managerRegistry,Request $request, OffreStageRepository $offreStageRepository): Response
    {
        $offre = $offreStageRepository->find($id);
        return $this->render('frontOffice/offre_stage/Details.html.twig', [
            'offre'=>$offre
        ]);
    }
    
    #[Route('/ChoixDemaine/{domaine}', name: 'ChoixDemaine')]
    public function ChoixDemaine($domaine, OffreStageRepository $offreStageRepository): Response
    {
        $offre = $offreStageRepository->find($domaine);
        return $this->render('frontOffice/offre_stage/recrutement.html.twig', [
            'offre'=>$offre
        ]);
    }
    #[Route('/Recrutement', name: 'Recrutement')]
    public function Recrutement(OffreStageRepository $offreStageRepository,Request $request,DemandeStageRepository $demandeStageRepository): Response
    {
        $search = [];
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);
        $recherche= $request->get('numero');
        $domaine = $request->get('domaine');
        $liste = $offreStageRepository->findAll();
        if ($form ->isSubmitted() && $form->isValid()){
            $id = $recherche;
            return $this->redirectToRoute('rechercheDemande',['numero' =>$id]);
        }
        
        return $this->render('frontOffice/offre_stage/recrutement.html.twig',[
            'offres' => $liste,
            'form' => $form->createView(),
        
        ]);
    }
    #[Route('/DemandeParOffres/{id}', name: 'DemandeParOffres')]
    public function DemandeParOffres($id, DemandeStageRepository $demandestageRepository,OffreStageRepository $offreStageRepository): Response
    {
        $demande = $demandestageRepository->findDemandesByOffre($id);
        $offre = $offreStageRepository->find($id);
        $name = $offre->getTitle();
        return $this->render('backOffice/demande_stage/affichage.html.twig', [
            'Demandes'=>$demande,
            'titre' => $name
        ]);
    }
    #[Route('/DeatailAdmin/{id}', name: 'DeatailAdmin')]
    public function DeatailAdmin($id, OffreStageRepository $offreStageRepository): Response
    {
      //  $demande = $demandestageRepository->findDemandesByOffre($id);
        $offre = $offreStageRepository->find($id);
        $name = $offre->getTitle();
        return $this->render('backOffice/offre_stage/DetailOffreAdmin.html.twig', [
            //'Demandes'=>$demande,
            'titre' => $name,
            'offre' => $offre
        ]);
    }
//    #[Route('/yesserA/{id}', name: 'yesserA')]
//    public function yesserA($id,DemandeStageRepository $demandeStageRepository,AnalyseCv $cvAnalyseur,OffreStageRepository $offreStageRepository): Response
//    {
//        $offre = $offreStageRepository->find($id);
//        $mots = $offre->getMotsCles();
//        $title = $offre->getTitle();
//        $listeDemande = $demandeStageRepository->findAll();
//        foreach ($listeDemande as $demande) {
//            $cheminFichier = $this->getParameter('uploads_directory') . '/' . $demande->getCv();
//            $score = $cvAnalyseur->analyseCV($cheminFichier, $mots);
//            //                dd($score,$id,$demande);
//            if ($score < 50) {
//                $to = $demande->getEmail();
//                $nom = $demande->getNom() . " " . $demande->getPrenom();
//                $subject = "Recommondation pour une offre";
//                $html = "<div>Bonjour {$nom}.<br>Vous etes recommondé pour l'offre {$title} sous ce chemin  127.0.0.1:8000/DetailsOffre/{$id} .<br>";
//                $this->emailService->sendEmail($to, $subject, $html);
//            }
//        }
//        return $this->redirectToRoute("afficheOffreStages");
//    }
    
    
}
