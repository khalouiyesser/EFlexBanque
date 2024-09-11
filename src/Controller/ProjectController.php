<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

#[Route('/project')]

class ProjectController extends AbstractController
{
  #[Route('/admin/list', name: 'app_project_admin', methods: ['GET'])]
  public function index(ProjectRepository $projectRepository): Response
  {
    return $this->render('admin/project/projects.html.twig', [
      'projects' => $projectRepository->findAll(),
    ]);
  }

  #[Route('/client/list/print', name: 'print', methods: ['GET'])]
  public function print(ProjectRepository $projectRepository): Response
  {
    return $this->render('client/project/print.html.twig', [
      'projects' => $projectRepository->findAll(),
    ]);
  }

  #[Route('/employe/list', name: 'app_project_listE', methods: ['GET'])]
  public function listE(ProjectRepository $projectRepository): Response
  {
    return $this->render('employe/project/projects.html.twig', [
      'projects' => $projectRepository->findAll(),
    ]);
  }
  #[Route('/client/list', name: 'app_project_indexclient', methods: ['GET'])]
  public function indexclient(ProjectRepository $projectRepository): Response
  {
    return $this->render('client/project/projects.html.twig', [
      'projects' => $projectRepository->findAll(),
    ]);
  }


  #[Route('/client/create', name: 'app_project_newC', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
  {
    $project = new Project();
    $form = $this->createForm(ProjectType::class, $project);
    $form->handleRequest($request);
    $uploadedFile = $request->files->get('my_image');
 
    if ($form->isSubmitted() && $form->isValid()) {
      // Get the currently logged-in user
      $user = $security->getUser();
      // Set the user on the project
      $project->setUser($user);
       $project->setImg("ayoub.jpeg");

      $entityManager->persist($project);
      $entityManager->flush();

      return $this->redirectToRoute('app_project_indexclient', [], Response::HTTP_SEE_OTHER);
    }
 
    return $this->renderForm('client/project/new.html.twig', [
      'project' => $project,
      'form' => $form,
    ]);
  }

  #[Route('/admin/create', name: 'app_project_new', methods: ['GET', 'POST'])]
  public function newC(Request $request, EntityManagerInterface $entityManager): Response
  {
    $project = new Project();
    $form = $this->createForm(ProjectType::class, $project);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $project->setImg("admin.png");
      $entityManager->persist($project);
      $entityManager->flush();

      return $this->redirectToRoute('app_project_admin', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('admin/project/new.html.twig', [
      'project' => $project,
      'form' => $form,
    ]);
  }
  #[Route('/employe/create', name: 'app_project_newE', methods: ['GET', 'POST'])]
  public function newE(Request $request, EntityManagerInterface $entityManager): Response
  {
    $project = new Project();
    $form = $this->createForm(ProjectType::class, $project);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($project);
      $entityManager->flush();

      return $this->redirectToRoute('app_project_listE', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('employe/project/new.html.twig', [
      'project' => $project,
      'form' => $form,
    ]);
  }


  #[Route('/{id}', name: 'app_project_indexshowsA', methods: ['GET'])]
  public function showA(Project $project): Response
  {
    $evenements = $project->getEvenements();
    $commentaires = [];

    foreach ($evenements as $evenement) {
      $commentaires[] = $evenement->getCommentaires();
    }

    return $this->render('admin/project/show.html.twig', [
      'project' => $project,
      'commentaires' => $commentaires,
      'evenements' => $evenements,
    ]);
  }




  #[Route('/{id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Project $project, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(ProjectType::class, $project);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('app_project_admin', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('admin/project/edit.html.twig', [
      'project' => $project,
      'form' => $form,
    ]);
  }
  #[Route('/{id}/edit1', name: 'app_project_edit1', methods: ['GET', 'POST'])]
  public function edit1(Request $request, Project $project, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(ProjectType::class, $project);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('my_project', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('client/project/edit1.html.twig', [
      'project' => $project,
      'form' => $form,
    ]);
  }

  #[Route('/edit/{id}', name: 'app_project_editE', methods: ['GET', 'POST'])]
  public function editE(Request $request, Project $project, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(ProjectType::class, $project);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('app_project_listE', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('employe/project/edit.html.twig', [
      'project' => $project,
      'form' => $form,
    ]);
  }

  #[Route('delete/{id}', name: 'app_project_delete', methods: ['GET','POST'])]
  public function delete($id , ManagerRegistry $managerRegistry , ProjectRepository $projectRepository): Response
  {
    $entityManager =$managerRegistry->getManager();
    $project= $projectRepository->find($id) ;
    $entityManager->remove($project);
    $entityManager->flush();
    return $this->redirectToRoute('app_project_admin', [], Response::HTTP_SEE_OTHER);
  }
  #[Route('delete1/{id}', name: 'app_project_delete1', methods: ['GET','POST'])]
  public function delete1($id , ManagerRegistry $managerRegistry , ProjectRepository $projectRepository): Response
  {
    $entityManager =$managerRegistry->getManager();
    $project= $projectRepository->find($id) ;
    $entityManager->remove($project);
    $entityManager->flush();
    return $this->redirectToRoute('my_project', [], Response::HTTP_SEE_OTHER);
  }
}
