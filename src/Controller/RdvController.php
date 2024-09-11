<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RdvType ;

use App\Entity\Rdv;
use App\Repository\RdvRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class RdvController extends AbstractController
{
    #[Route('/rdv', name: 'app_rdv')]
    public function index(): Response
    {
        return $this->render('rdv/index.html.twig', [
            'controller_name' => 'RdvController',
        ]);
    }
    #[Route('/rdvcalendrier', name: 'app_rdv')]
    public function calendrierrdv(RdvRepository $rdvRepository): Response
    {
        $events = $rdvRepository->findAll();
        $rdvs = [];
        foreach ($events as $event) {
            $startDateTime = $event->getDateRdv()->format('Y-m-d') . ' ' . $event->getHeure()->format('H:i:s');

            $rdvs[] = [
                'id' => $event->getId(),
                'title' => $event->getEmployeName(), // Use whatever field you want as the event title
                'start' =>$startDateTime
                
            ];
        }
        $rdvsJson = json_encode($rdvs);

    // Pass the JSON data to the Twig template
    return $this->render('rdv/calendrier.html.twig', compact('rdvsJson'));

    }
    
    #[Route('/listerdv', name: 'app_listerdv')]
    public function listeacredit(RdvRepository $rdvRepository): Response
    {
        $rdvs=$rdvRepository->findAll();
    
        return $this->render('credit/listerdv.html.twig',["rdvs"=>$rdvs,'smsSent'=>FALSE]);
    }
    #[Route('/ajouterrdv', name: 'app_ajouterrdv')]
    public function ajoutercredit(ManagerRegistry $doctrine,Request $request):Response{

        $rdv=new Rdv();
        $form=$this->createForm(RdvType::class,$rdv);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->persist($rdv);
            $em->flush();
        }
        return $this->render('credit/ajouterrdv.html.twig',[
            'form' => $form->createView(),
        ]);
}

#[Route('/editrdv/{id}', name: 'app_modifierrdv')]
public function modifiercredit(ManagerRegistry $doctrine,$id,RdvRepository $rdvRepository,Request $request):Response{
    $rdv=$rdvRepository->find($id);
    $form=$this->createForm(RdvType::class,$rdv);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
        $em=$doctrine->getManager();
        $em->persist($rdv);
        $em->flush();
        return $this->redirectToRoute('app_listerdv');
    }
    return $this->render('credit/editrdv.html.twig',[
        'formc' => $form->createView(),
    ]);
}
#[Route('/deleterdv/{id}', name: 'app_deleterdv')]
public function deleteCredit(RdvRepository $rdvRepository, ManagerRegistry $doctrine, $id): Response
{
    $credit = $rdvRepository->find($id);

    if (!$credit) {
        throw $this->createNotFoundException('rdv not found');
    }

    $em = $doctrine->getManager();
    $em->remove($credit);
    $em->flush();

    return $this->redirectToRoute('app_listerdv');
}

}