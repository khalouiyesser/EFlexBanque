<?php

namespace App\Controller;

use App\Entity\ReponseCommentaire;
use App\Form\ReponseCommentaireType;
use App\Repository\ReponseCommentaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireHadhemiRepository;

class ReponseCommentaireController extends AbstractController
{
    #[Route('/reponseCommentaire/{id}', name: 'app_reponse_commentaireId')]
    public function reponseCommentaireId(ReponseCommentaire $reponseCommentaire): Response
    {
        return $this->render('reponse_commentaire/show.html.twig', [
            'reponseCommentaire' => $reponseCommentaire,
        ]);
    }
 

    #[Route('/reponseCommentaire', name: 'reponseCommentaire', methods: ['GET'])]
    public function reponseCommentaire(ReponseCommentaireRepository $reponseCommentaireRepository , CommentaireHadhemiRepository $commentaireRepository): Response
    {
       
         
        return $this->render('reponse_commentaire/index.html.twig', [
            'reponsesCom' => $reponseCommentaireRepository->findAll(),
            'Commentaires' => $commentaireRepository->findAll(),

        ]);
    }





    #[Route('/new/{id}', name: 'reponseCommentaireAdd', methods: ['GET', 'POST'])]
public function reponseCommentaireAdd($id, Request $request, EntityManagerInterface $entityManager, CommentaireHadhemiRepository $commentaireRepository): Response
{
    // Récupérer l'entité CommentaireHadhemi en fonction de l'ID fourni
    $commentaire = $commentaireRepository->find($id);
    
    if (!$commentaire) {
        throw $this->createNotFoundException('CommentaireHadhemi not found');
    }

    $reponseCommentaire = new ReponseCommentaire();
    $dateAujourdhui = new \DateTime();
    $reponseCommentaire->setDateRepCom($dateAujourdhui);
   
    $form = $this->createForm(ReponseCommentaireType::class, $reponseCommentaire);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $reponseCommentaire->setCommentaire($commentaire); // Associer la réponse au commentaireHadhemi
        $entityManager->persist($reponseCommentaire);
        $entityManager->flush();

        return $this->redirectToRoute('reponseCommentaire', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reponse_commentaire/add.html.twig', [
        'reponseCommentaire' => $reponseCommentaire,
        'form' => $form,
    ]);
}

    
    #[Route('/{id}/reponseCommentaire/edit', name: 'reponseCommentaireEdit', methods: ['GET', 'POST'])]
    public function reponseCommentaireEdit($id,Request $request,ManagerRegistry $managerRegistry,ReponseCommentaireRepository $reponseCommentaireRepository): Response
    {
      $reponsecom = $reponseCommentaireRepository->find($id);
      $form = $this->createForm(ReponseCommentaireType::class,$reponsecom);
      $form->handleRequest($request);
      $em = $managerRegistry->getManager();
      if($form->isSubmitted() && $form->isValid()){
        $em->persist($reponsecom);
        $em->flush();
            return $this->redirectToRoute('reponseCommentaire', [], Response::HTTP_SEE_OTHER);
        }
      

        return $this->render('reponse_commentaire/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

 



    #[Route('/reponseCommentaire/delete/{id}', name: 'reponseCommentairedelete')]
    public function reponseCommentairedelete($id,Request $request,ManagerRegistry $managerRegistry,ReponseCommentaireRepository $reponseCommentaireRepository): Response
    {
         $reponse = $reponseCommentaireRepository->find($id);
        $em = $managerRegistry->getManager();
         $em->remove($reponse);
        $em->flush();
        return $this->redirectToRoute('reponseCommentaire');
    }
}
