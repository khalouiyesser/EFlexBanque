<?php

namespace App\Controller;

use App\Entity\CommentaireHadhemi;
use App\Form\CommentaireHadhemiType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireHadhemiRepository;
use App\ServiceReclamation\UploaderServiceRec;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;


//admin

#[Route('/commentaireHadhemi')]
class CommentaireHadhemiController extends AbstractController
{
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireHadhemiRepository $commentaireRepository): Response
    {
        return $this->render('commentaireHadhemi/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    //employe

    #[Route('/listeComEmploye', name: 'app_commentaireemploye_index', methods: ['GET'])]
    public function employe(CommentaireHadhemiRepository $commentaireRepository): Response
    {
        return $this->render('employe/listeCommentairesEmp.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager ): Response
{
    $commentaire = new CommentaireHadhemi();
    $dateAujourdhui = new DateTime();
$commentaire->setDateCreation($dateAujourdhui);

    $form = $this->createForm(CommentaireHadhemiType::class, $commentaire);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($commentaire);
        $entityManager->flush();

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('commentaireHadhemi/new.html.twig', [
        'commentaireHadhemi' => $commentaire,
        'form' => $form,
    ]);
}
// Front
#[Route('/CommentaireAddFront/{id}', name: 'app_CommentaireAddFront', methods: ['GET', 'POST'])]

public function CommentaireAddFront($id, Request $request, EntityManagerInterface $entityManager, ArticleRepository $articleRepository , UploaderServiceRec $uploadServiceRec): Response
{
  
    $article = $articleRepository->find($id);
        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $dateAujourdhui = new DateTime();
        $nomAutCom = $request->request->get('nomAutCom');
        $contenu = $request->request->get('contenu');
        $imageU = $request->files->get('imageU');

        $commentaire = new CommentaireHadhemi();
       
        $commentaire->setDateCreation($dateAujourdhui);
        $commentaire->setArticle($article);
        if ($imageU) {

            $fileName = $uploadServiceRec->uploadFileRec($imageU);
           
            $commentaire->setImageU($fileName);
        }
    
        $commentaire->setNomAutCom($nomAutCom);
        $commentaire->setContenu($contenu);
        $contenu = $commentaire->getContenu();
        $filteredContenu = $this->filterwords($contenu);
        $commentaire->setContenu($filteredContenu);

        $entityManager->persist($commentaire);
        $entityManager->flush();

            return $this->redirectToRoute('app_article_showfront', ['id' => $id] , Response::HTTP_SEE_OTHER);
        
    }
    

   














    #[Route('/{id}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(CommentaireHadhemi $commentaire): Response
    {
        return $this->render('commentaireHadhemi/show.html.twig', [
            'commentaireHadhemi' => $commentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CommentaireHadhemi $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireHadhemiType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaireHadhemi/edit.html.twig', [
            'commentaireHadhemi' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_commentaire_delete', methods: ['GET', 'POST'])]
    public function delete($id, ManagerRegistry $managerRegistry, CommentaireHadhemiRepository $commentaireRepository): Response
    {
        $entityManager = $managerRegistry->getManager();
        $commentaire = $commentaireRepository->find($id);

        if (!$commentaire) {
            throw $this->createNotFoundException('CommentaireHadhemi non trouvé.');
        }

        $entityManager->remove($commentaire);
        $entityManager->flush();

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }

    public function filterwords($text)
    {
        $filterWords = array('hate', 'bhim', 'msatek', 'fuck', 'slut', 'fucku' ,'free_israel');
        $str = "";
        $data = preg_split('/\s+/',  $text);
        foreach($data as $s){
            $g = false;
            foreach ($filterWords as $lib) {
                if($s == $lib){
                    $t = "";
                    for($i =0; $i<strlen($s); $i++) $t .= "*";
                    $str .= $t . " ";
                    $g = true;
                    break;
                }
            }
            if(!$g)
            $str .= $s . " ";
        }
        return $str;
    }


}
