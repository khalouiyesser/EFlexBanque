<?php

namespace App\Controller;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\ServiceReclamation\UploaderServiceRec;
use DateTime;


#[Route('/reponse')]
class ReponseController extends AbstractController
{
    #[Route('/', name: 'app_reponse_index', methods: ['GET'])]
    public function index(ReponseRepository $reponseRepository , ReclamationRepository $reclamationRepository): Response
    {
        
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
            'reclamations' => $reclamationRepository->findAll(),
        
        ]);
    }
    
    
    #[Route('/reponseClient/{id}', name: 'app_reponseClient_showId', methods: ['GET', 'POST'])]
    public function reponseClient($id, ReclamationRepository $reclamationRepository, ReponseRepository $reponseRepository): Response
    {
        
        $reponses = $reponseRepository->findReponseByReclamation($id);
        $reclamation =$reclamationRepository->find($id);
        return $this->render('client/afficherRecRep.html.twig', [
            'reclamation' => $reclamation,
            'reponses' => $reponses,
        ]);
    }
    #[Route('/yesser/{id}', name: 'app_reponse_showy', methods: ['GET'])]
    
    public function showreponsey($id , ReponseRepository $reponseRepository): Response
    {
        $a = $reponseRepository->findOneBySomeField($id);
        
        return $this->render('reponse/show.html.twig', [
            'reponse' => $a,
        ]);
    }
    
    // #[Route('/new/{id}/{var}', name: 'app_reponse_new', methods: ['GET', 'POST'])]
    // public function new($id , $var ,Request $request, EntityManagerInterface $entityManager ,Reclamation $reclamation): Response
    // {
    //     $reponse = new Reponse();
    
    //     $form = $this->createForm(ReponseType::class, $reponse);
    //     $form->handleRequest($request);
    
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($reponse);
    //         $entityManager->flush();
    //         $reponse->setReclamation($reclamation);
    
    //         return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
    //     }
    
    //     return $this->renderForm('reponse/new.html.twig', [
    //         'reponse' => $reponse,
    //         'form' => $form,
    //     ]);
    // }
    #[Route('/new/{id}', name: 'app_reponse_new', methods: ['GET', 'POST'])]
    public function new($id, Request $request, EntityManagerInterface $entityManager, ReclamationRepository $reclamationRepository , UploaderServiceRec $uploadServiceRec ): Response
    {
        // Retrieve the Reclamation entity based on the provided id
        $reclamation = $reclamationRepository->find($id);
        $reclamation->setStatutRec("Traitée");
        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }
        
        $reponse = new Reponse();
        $user=$this->get('security.token_storage')->getToken()->getUser();
        $reponse->setUser($user);
        $userAddress = $user->getEmail();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $fileRec = $form->get('pieceJRep')->getData();
            if ($fileRec) {
                $fileName = $uploadServiceRec->uploadFileRec($fileRec);
                $reponse->setPieceJRep($fileName);
            }
            $dateAujourdhui = new DateTime();
            $reponse->setDateRep($dateAujourdhui);
            $reponse->setAdrRep($userAddress);
            $reponse->setReclamation($reclamation); // Associate the Reponse with the Reclamation
            $entityManager->persist($reponse);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_reponse_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }
    
    
    #[Route('/edit/{id}', name: 'app_reponse_edit', methods: ['GET', 'POST'])]
    public function edit($id ,Request $request, Reponse $reponse, EntityManagerInterface $entityManager , UploaderServiceRec $uploadServiceRec , ReclamationRepository $reclamationRepository ): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        $reclamation = $reclamationRepository->find($id);
        $user=$this->get('security.token_storage')->getToken()->getUser();
        $reponse->setUser($user);
        $userAddress = $user->getEmail();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $fileRec = $form->get('pieceJRep')->getData();
            if ($fileRec) {
                $fileName = $uploadServiceRec->uploadFileRec($fileRec);
                $reponse->setPieceJRep($fileName);
            }
            $reponse->setAdrRep($userAddress);
            $dateAujourdhui = new DateTime();
            $reponse->setDateRep($dateAujourdhui);
            
            $entityManager->flush();
            
            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'reclamation' => $reclamation,
            
            'form' => $form,
        ]);
    }
    
    #[Route('delete/{id}', name: 'app_reponse_delete', methods: ['GET','POST'])]
    public function delete($id , ManagerRegistry $managerRegistry , ReponseRepository $reponseRepository): Response
    {
        $entityManager =$managerRegistry->getManager();
        $reponse= $reponseRepository->find($id) ;
        
        $entityManager->remove($reponse);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
