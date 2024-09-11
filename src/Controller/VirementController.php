<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Virement;
use App\Form\VirementType;
use App\Repository\CompteRepository;
use App\Repository\VirementRepository;
use App\Service\uploadFile;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\TwilioSmsService;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;








class VirementController extends AbstractController
{
    
    public string $directory = 'uploads_directory';
    
    #[Route('/virements', name: 'app_virement')]
    public function index(): Response
    {
        return $this->render('virement/Virements.html.twig', [
            'controller_name' => 'VirementController',
        ]);
    }
    
    #[Route('/addvirement', name: 'addvirement')]
    public function addvirement(CompteRepository $repository, Request $request, ManagerRegistry $managerRegistry, SluggerInterface $slugger, uploadFile $uploadFile): Response
    {
        $virement = new Virement ();
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $rib = $user->getRib();
        $compte = $repository->findOneBySomeField($rib);
        $virement->setRIB($user->getRib());
        $virement->setCompte($compte);
        $virement->setUser($user);
        $form = $this->createForm(VirementType::class, $virement);
        $em = $managerRegistry->getManager();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photoCinV')->getData();
            $photoCinV = $uploadFile->uploadFile($photo);
            $virement->setPhotoCinV($photoCinV);
            $virement->setDecisionV("encours");
            
            $em->persist($virement);
            $em->flush();
            return $this->redirectToRoute('historiqueV');
        }
        
        return $this->render('frontOffice/Client/virement/DemandeVirement.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/showDemande', name: 'showDemande')]
    public function showDemande(VirementRepository $virementRepository): Response
    {
        $liste = $virementRepository->listeDesVirements("encours");
        return $this->render('backoffice/admin/virement/Virements.html.twig', [
            'virements' => $liste,
        ]);
        
    }
    
    #[Route('/showHistoriqueV', name: 'showHistoriqueV')]
    public function showHistoriqueV(VirementRepository $virementRepository): Response
    
    {
        $liste = $virementRepository->listeDesVirementsAccepte("Approuvé");
        return $this->render('backoffice/admin/virement/historiqueVirement.html.twig', [
            'virement' => $liste,
        ]);
    }
    
    
    #[Route('/historiqueV', name: 'historiqueV')]
    public function historiqueV(VirementRepository $virementRepository): Response
    
    {
        $liste = $virementRepository->findAll(true);
        return $this->render('frontOffice/Client/virement/historiqueV.html.twig', [
            'virement' => $liste,
        ]);
    }
    
    
    #[Route('/showDemandeE', name: 'showDemandeE')]
    public function showDemandeE(VirementRepository $virementRepository): Response
    {
        $liste = $virementRepository->listeDesVirements("encours ");
        return $this->render('backoffice/Employe/virement/listVirementEmpl.html.twig', [
            'virements' => $liste,
        ]);
        
    }
    
    #[Route('/showHistoriqueE', name: 'showHistoriqueE')]
    public function showHistoriqueE(VirementRepository $virementRepository): Response
    {
        $liste = $virementRepository->listeDesVirementsAccepte('Approuvé');
        return $this->render('backoffice/Employe/virement/virementE.html.twig', [
            'virements' => $liste,
        ]);
        
    }
    
    #[Route('/ApprouverVirementEmp/{id}', name: 'ApprouverVirementEmp')]
    public function ApprouverVirementEmp($id, ManagerRegistry $managerRegistry, VirementRepository $virementRepository): Response
    
    {
        $virement = $virementRepository->find($id);
        $virement->setDecisionV("Approuvé");
        $body ="Bonjour ' . ', ' .
                    'Nous sommes heureux de vous informer que votre demande de virement ' .
                    'a été approuvée avec succès. ' .
                    'Cordialement, [ EFB]";
        $virementRepository->sms('+21658911742' , $body);
        
        $emm = $managerRegistry->getManager();
        $emm->persist($virement);
        $emm->flush();
        return $this->redirectToRoute('showHistoriqueE');
    }
    
    #[Route('/deleteVirementEmp/{id}', name: 'deleteVirementEmp', methods: ['GET', 'POST'])]
    public function deleteVirementEmp($id, ManagerRegistry $managerRegistry, VirementRepository $virementRepository): Response
    {
        
        $emm = $managerRegistry->getManager();
        $idremove = $virementRepository->find($id);
        $idremove->setDecisionV("Réfusé");
        $body ="Bonjour ' . ', ' .
                    'Nous vous informons que votre demande de virement ' .
                    'a été refusée. ' .
                    'Cordialement, [ EFB]";
        $virementRepository->sms('+21658911742' , $body);
        $idremove->setDecisionV('Refuser');
        $emm->persist($idremove);
        $emm->flush();
        return $this->redirectToRoute('showDemandeE');
    }
    
    #[Route('/deleteVirementAdm/{id}', name: 'deleteVirementAdm', methods: ['GET', 'POST'])]
    public function deleteVirementAdm($id, ManagerRegistry $managerRegistry, VirementRepository $virementRepository): Response
    {
        
        $emm = $managerRegistry->getManager();
        $idremove = $virementRepository->find($id);
        $idremove->setDecisionV('Refuser');
        $emm->persist($idremove);
        $emm->flush();
        return $this->redirectToRoute('showHistoriqueV');
    }
    
    
    #[Route('/ApprouverVirement/{id}', name: 'ApprouverVirement')]
    public function ApprouverVirement($id, ManagerRegistry $managerRegistry , VirementRepository $virementRepository , TwilioSmsService $twilioSmsService ):Response
    
    {
        $virement=$virementRepository->find($id);
        $virement->setDecisionV("Approuvé");
        $body= "'Bonjour ' . ', ' .
                    'Nous sommes heureux de vous informer que votre demande de virement ' .
                    'a été approuvée avec succès. ' .
                    'Cordialement, [ EFB]'";
        $virementRepository->sms('+21658911742' , $body);
        $emm=$managerRegistry->getManager();
        $emm->persist($virement);
        $emm->flush();
        return $this->redirectToRoute('showHistoriqueV');
    }
    /*  #[Route('/sendSmsToClient', name: 'sendSmsToClient')]
      public function sendSmsToClient(Request $request, TwilioSmsService $twilioSmsService): Response
      {
  
          $phoneNumber =$request->request->get('phoneNumber');
  
          $name=$request->request->get('NometPrenom');
  
          $text=$request->request->get('text');
  
          $number_test=$_ENV['+19492696499'];// Numéro vérifier par twilio. Un seul numéro autorisé pour la version de test.
  
          //Appel du service
          $twilioSmsService->sendSmsToClient($number_test ,$name,$text);
  
          return $this->render('sms/index.html.twig', ['smsSent'=>true]);
      }*/
    
    #[Route('/deleteVirement/{id}', name: 'deleteVirement')]
    public function deleteVirement($id, ManagerRegistry $managerRegistry, VirementRepository $virementRepository): Response
    {
        $emm = $managerRegistry->getManager();
        $idremove = $virementRepository->find($id);
        $emm->remove($idremove);
        $emm->flush();
        return $this->redirectToRoute('historiqueV');
    }
    
    
    #[Route('/modifierVirement/{id}', name: 'modifierVirement')]
    public function modifierVirement($id, ManagerRegistry $managerRegistry, VirementRepository $virementRepository, Request $request): Response
    {
        $em = $managerRegistry->getManager();
        $idData = $virementRepository->find($id);
        $form = $this->createForm(VirementType::class, $idData);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $em->persist($idData);
            $em->flush();
            return $this->redirectToRoute('historiqueV');
            
        }
        return $this->renderForm('frontOffice/Client/virement/DemandeVirement.html.twig', [
            'form' => $form
        ]);
    }


    public function virements(VirementRepository $virementRepository): Response
    {
        // Récupérer le nombre de virements effectués par le client par type
        $TypeVirement = $virementRepository->getNombreVirementsParType($this->getUser(), 'Ecoresponsabilité');


        return $this->render('frontOffice/Client/dashboard.html.twig', [
            'TypeVirement' => $TypeVirement,
        ]);
    }


//    #[Route('/virements', name: 'virements')]
//    public function virements(VirementRepository $virementRepository): Response
//    {
//        // Récupérer le nombre de virements effectués par le client
//        $nombreVirements = $virementRepository->getNombreVirementsDuClient($this->getUser());
//
//        // Créer le diagramme à partir des données
//        $pieChart = new PieChart();
//        $pieChart->getData()->setArrayToDataTable([
//            ['Type', 'Nombre de virements'],
//            ['Virements effectués', $nombreVirements],
//            ['Autres virements', $totalAutresVirements - $nombreVirements], // ajustez-le en fonction de vos données
//        ]);
//        $pieChart->getOptions()->setTitle('Nombre de virements effectués');
//        $pieChart->getOptions()->setHeight(400);
//        $pieChart->getOptions()->setWidth(600);
//        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
//        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
//        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
//        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
//
//        return $this->render('virement/Virements.html.twig', [
//            'controller_name' => 'VirementController',
//            'pieChart' => $pieChart,
//        ]);
//    }

    
}