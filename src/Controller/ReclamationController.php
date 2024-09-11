<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Service\Mailing;
use App\ServiceReclamation\UploaderServiceRec;

use App\Repository\ReclamationRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;




#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    //private $uploadServiceRec; // Déclarez la propriété pour le service UploadServiceRec
    
    // Ajoutez le constructeur avec le service UploadServiceRec comme argument
    //public function __construct(UploadServiceRec $uploadServiceRec)
    //{
    //    $this->uploadServiceRec = $uploadServiceRec;
    //}
    public Mailing $emailService;
    public function __construct(Mailing $emailService)
    {
        $this->emailService = $emailService;
    }
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }
    
    #[Route('/showRecEmploye', name: 'app_showRecEmploye_index', methods: ['GET'])]
    public function showRecEmploye(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('employe/reclamationEmploye.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }
    
    
    
    
    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UploaderServiceRec $uploadServiceRec ): Response
    {
        
        $reclamation = new Reclamation();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $reclamation->setUser($user);
        
        
        $userAddress = $user->getEmail();
        $userName=$user->getName();
        
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $fileRec = $form->get('pieceJRec')->getData();
            if ($fileRec) {
                $fileName = $uploadServiceRec->uploadFileRec($fileRec);
                $reclamation->setPieceJRec($fileName);
            }
            
            $dateAujourdhui = new DateTime();
            $reclamation->setDateRec($dateAujourdhui);
            $reclamation->setAdrRec($userAddress);
            $reclamation->setNomAutRec($userName);
            $reclamation->setStatutRec("En cours de traitement");
            $reclamation->setUser($user); // Set the user associated with the reclamation
            $entityManager->persist($reclamation);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_reclamation_showId', ['id' => $reclamation->getId()], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
    
    #[Route('/newFrontFooter', name: 'reclamation_ajouter', methods: ['GET', 'POST'])]
    
    public function ajouterReclamation(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données du formulaire
        $nomAutRec = $request->request->get('nomAutRec');
        $adrRec = $request->request->get('adrRec');
        $objetRec = $request->request->get('objetRec');
        $contenuRec = $request->request->get('contenuRec');
        $depRec = $request->request->get('depRec');
        $dateAujourdhui = new DateTime();
        // Créer une nouvelle instance de l'entité Reclamation
        $reclamation = new Reclamation();
        $reclamation->setNomAutRec($nomAutRec);
        $reclamation->setAdrRec($adrRec);
        $reclamation->setObjetRec($objetRec);
        $reclamation->setContenuRec($contenuRec);
        $reclamation->setDepRec($depRec);
        $reclamation->setDateRec($dateAujourdhui);
        $reclamation->setStatutRec("En cours de traitement");
        $reclamation->setPieceJRec("Aucune pièce jointe");
        // Enregistrer la réclamation dans la base de données
        $entityManager->persist($reclamation);
        $entityManager->flush();
        // Redirection vers une autre page après l'ajout de la réclamation
        return $this->redirectToRoute('frontVisiteur');
    }
    
    
    #[Route('/newFrontAcceuil', name: 'app_reclamationFront_new', methods: ['GET', 'POST'])]
    public function newFront(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données du formulaire
        $objet = $request->request->get('objetRec');
        $contenu = $request->request->get('contenuRec');
        $adresse = $request->request->get('adrRec');
        $nom = $request->request->get('nomAutRec');
        $departement = $request->request->get('depRec');
        
        
        // Convertir la chaîne de caractères de date en objet DateTime
        $dateAujourdhui = new DateTime();
        
        // Créer une nouvelle instance de l'entité Reclamation
        $reclamation = new Reclamation();
        $reclamation->setObjetRec($objet);
        $reclamation->setContenuRec($contenu);
        $reclamation->setAdrRec($adresse);
        $reclamation->setNomAutRec($nom);
        $reclamation->setDepRec($departement);
        $reclamation->setDateRec($dateAujourdhui);
        $reclamation->setStatutRec("En cours de traitement");
        $reclamation->setPieceJRec("Aucune pièce jointe");
        // Enregistrer la réclamation dans la base de données
        $entityManager->persist($reclamation);
        $entityManager->flush();
        $subject = "Réclamation envoyée avec succès";
        $html = "Votre reclamation a été envoyée avec succès. <br>
        Nous vous répondre ultérierement.";
        $this->emailService->sendEmail($adresse,$subject,$html);
        
        // Redirection vers une autre page après l'ajout de la réclamation
        return $this->redirectToRoute('frontVisiteur');
    }
    
    
    
    
    
    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
    
    
    #[Route('/reclamationClient/{id}', name: 'app_reclamation_showId', methods: ['GET'])]
    public function showId($id,ReclamationRepository $reclamationRepository): Response
    {
        $b = $reclamationRepository->findAll();
        $a = $reclamationRepository->findByExampleField($id);
        
        return $this->render('client/reclamation.html.twig', [
            'reclamation' => $a,
        ]);
    }
    
    
    
    
    
    #[Route('delete/{id}', name: 'app_reclamation_delete', methods: ['GET','POST'])]
    public function delete($id , ManagerRegistry $managerRegistry , ReclamationRepository $reclamationRepository): Response
    {
        $entityManager =$managerRegistry->getManager();
        $reclamation= $reclamationRepository->find($id) ;
        $entityManager->remove($reclamation);
        $entityManager->flush();
        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('deleteEmp/{id}', name: 'app_reclamationEmploye_delete', methods: ['GET','POST'])]
    public function deleteEmp($id , ManagerRegistry $managerRegistry , ReclamationRepository $reclamationRepository): Response
    {
        $entityManager =$managerRegistry->getManager();
        $reclamation= $reclamationRepository->find($id) ;
        $entityManager->remove($reclamation);
        $entityManager->flush();
        return $this->redirectToRoute('app_showRecEmploye_index', [], Response::HTTP_SEE_OTHER);
    }
    
    
    #[Route('/deleterec/{id}', name: 'app_reclamationclient_delete', methods: ['GET','POST'])]
    public function reclamationclient($id, ManagerRegistry $managerRegistry, ReclamationRepository $reclamationRepository): Response
    {
        $entityManager = $managerRegistry->getManager();
        $reclamation = $reclamationRepository->find($id);
        
        if (!$reclamation) {
            throw $this->createNotFoundException('La réclamation n\'existe pas.');
        }
        
        $entityManager->remove($reclamation);
        $entityManager->flush();
        
        // Redirection vers l'index des réclamations pour cet utilisateur
        return $this->redirectToRoute('app_reclamation_showId', ['id' => $reclamation->getUser()->getId()], Response::HTTP_SEE_OTHER);
    }
    
    
    
}
