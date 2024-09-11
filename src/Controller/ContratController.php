<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Form\ContratType;
use App\Repository\ContratRepository;
use App\Repository\StageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ContratController extends AbstractController
{
    #[Route('/contrat', name: 'app_contrat')]
    public function index(): Response
    {
        return $this->render('backOffice/contrat/BaseFront.html.twig', [
            'controller_name' => 'ContratController',
        ]);
    }
    #[Route('/addContrat', name: 'addContrat')]
    public function addContrat(Request $request,ManagerRegistry $managerRegistry,SluggerInterface $slugger): Response
    {
        $titre = "Ajouter un contrat";
        
        $stage = new Contrat();
        $form = $this->createForm(ContratType::class, $stage);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $x = $managerRegistry->getManager();
            $x->persist($stage);
            $x->flush();
        }
        return $this->render('backOffice/contrat/add.html.twig', [
            'form' => $form->createView(),
            'titre'=> $titre
        ]);
    }
    
    
    
    #[Route('/AffichageDesContrats', name: 'AffichageDesContrats')]
    public function AffichageDesContrats(ContratRepository $contratRepository): Response
    {
        
        $liste = $contratRepository->findAll();
        return $this->render('backOffice/contrat/affichageContrat.html.twig', [
            'contrat' => $liste,
            
        ]);
    }
    #[Route('/deleteContrat/{id}', name: 'deleteContrat')]
    public function deleteContrat($id, ManagerRegistry $manager, ContratRepository $contratRepository): Response
    {
        $emm = $manager->getManager();
        $idremove = $contratRepository->find($id);
        $emm->remove($idremove);
        $emm->flush();
        return $this->redirectToRoute('demandeStage');
    }
    
    #[Route('/modifierContrat/{id}', name: 'modifierContrat')]
    public function modifierContrat($id, ManagerRegistry $manager, ContratRepository $stageRepository, Request $request,): Response
    {
        $titre = "Modifier contrat";
        $emr = $manager->getManager();
        $idData = $stageRepository->find($id);
        $form = $this->createForm(ContratType::class, $idData);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $emr->persist($idData);
            $emr->flush();
            return new Response("update with succcess");
        }
        return $this->renderForm('backOffice/contrat/add.html.twig', [
            'form' => $form,
            'titre' => $titre
        ]);
    }
}
