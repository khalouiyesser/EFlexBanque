<?php

namespace App\Controller;
use App\Entity\Credit;

use App\Repository\CreditRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CreditType ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Demandestage;
use App\Form\DemandeStageType;
use App\Repository\DemandeStageRepository;
use App\Repository\OffreStageRepository;
use App\Service\Mailing;
use App\Service\uploadFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;





class CreditController extends AbstractController
 
{
    public Mailing $emailService;
    public function __construct(Mailing $emailService)
    {
        $this->emailService = $emailService;
    }
    public  $directory = 'uploads_directory';
    #[Route('/credit', name: 'app_credit')]
    public function index(): Response
    {
        return $this->render('credit/index.html.twig', [
            'controller_name' => 'CreditController',
        ]);

    }

    #[Route('/listecredit', name: 'app_listecredit')]
    public function listeacredit(CreditRepository $creditRepository): Response
    {
        $credits=$creditRepository->findAll();
    
        return $this->render('credit/listecredit.html.twig',["credits"=>$credits]);
    }
    #[Route('/suivrecredit', name: 'app_suivrecredit')]
    public function suivrecredit(CreditRepository $creditRepository): Response
    {
        $credits=$creditRepository->findAll();
    
        return $this->render('credit/suivrecredit.html.twig',["credits"=>$credits]);
    }

    #[Route('/ajoutercredit', name: 'app_ajoutercredit')]
    public function ajoutercredit(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        $credit = new Credit();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        ($user->getRib());
        $credit->setIdClient($user->getRib());

        



        $form = $this->createForm(CreditType::class, $credit);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $credit->setUser($user); 
            $file = $form->get('fichesalire')->getData(); // Use get('fieldName')->getData() to retrieve file data from the form
            if ($file) {
                $uploads_directory = $this->getParameter('uploads_directory');
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // Generate a safe filename to avoid conflicts
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
    
                try {
                    // Move the file to the upload directory
                    $file->move(
                        $uploads_directory,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error, you might want to log this
                    throw new \Exception('Failed to upload the file: ' . $e->getMessage());
                }
    
                // Set the file name in the Credit entity
                $credit->setFichesalire($newFilename);
            }
    
            $em = $doctrine->getManager();
            $em->persist($credit);
            $em->flush();
    
            return $this->redirectToRoute('app_suivrecredit');
        }
    
        return $this->render('credit/ajoutercredit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/editcredit/{id}', name: 'app_modifiercredit')]
    public function modifiercredit(ManagerRegistry $doctrine, $id, CreditRepository $creditRepository, Request $request): Response
    {
        $credit = $creditRepository->find($id);
        $oldFichesalire = $credit->getFichesalire(); // Get the current filename
        $form = $this->createForm(CreditType::class, $credit);
        $form->handleRequest($request);
        $oldFichesalire = $credit->getFichesalire();
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $uploads_directory = $this->getParameter('upload_directory');

            // Handle file upload
            $file = $form['fichesalire']->getData();
            if ($file) {
                // Generate a unique name for the file
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
    
                // Move the file to the directory where files are stored
                $file->move(
                    $uploads_directory,
                    $fileName
                );
    
                // Update the 'fichesalire' property of the Credit entity
                $credit->setFichesalire($fileName);
            } else {
                // If no new file is uploaded, retain the old filename
                $credit->setFichesalire($oldFichesalire);
            }
    
            // Persist the modified Credit entity
            $em->persist($credit);
            $em->flush();
    
            return $this->redirectToRoute('app_listecredit');
        }
    
        return $this->render('credit/editcredit.html.twig', [
            'formc' => $form->createView(),
            'oldFichesalire' => $oldFichesalire, // Pass the old filename to the template
        ]);
    }
#[Route('/deletecredit/{id}', name: 'app_deletecredit')]
public function deleteCredit(CreditRepository $creditRepository, ManagerRegistry $doctrine, $id): Response
{
    $credit = $creditRepository->find($id);

    if (!$credit) {
        throw $this->createNotFoundException('Credit not found');
    }

    $em = $doctrine->getManager();
    $em->remove($credit);
    $em->flush();

    return $this->redirectToRoute('app_listecredit');
}

#[Route('/approuvercredit/{id}', name: 'app_approuvercredit')]
public function approuvercredit(ManagerRegistry $doctrine,MailerInterface $mailer,CreditRepository $creditRepository,$id, Mailing $mailing): Response
{
    $credit = $creditRepository->find($id);
     $credit->setStatus("accepte");
     $em = $doctrine->getManager();
     $em->persist($credit);
     $em->flush();
    $to = $credit->getUser()->getEmail();
    $subject = "Email";
    $content = 'Nous avons le plaisir de vous informer que votre demande de crédit a été officiellement acceptée';
   /* $email = (new Email())
    ->from('yesser.khaloui@etudiant-fst.utm.tn')
    ->to($to)
    //->cc('cc@example.com')
    //->bcc('bcc@example.com')
    // ->replyTo($this->replyTo)
    //->priority(Email::PRIORITY_HIGH)
    ->subject($subject)
//            ->text('Sending emails is fun again!')
    ->html($content);
   // $mailer->send($email);*/
    $this->emailService->sendEmail($to, $subject, $content);
    return $this->redirectToRoute('app_listecredit');
}

#[Route('/refusercredit/{id}', name: 'app_refusercredit')]
public function refusercredit($id, CreditRepository $creditRepository, ManagerRegistry $doctrine): Response
{
    $credit = $creditRepository->find($id);
    $credit->setStatus("refuse");
    
    $em = $doctrine->getManager();
    $em->persist($credit);
    $em->flush();
    
    $to = $credit->getUser()->getEmail();
    $subject = "Email";
    $content = 'Après une évaluation approfondie de votre dossier, nous regrettons de vous informer que votre demande n\'a pas été approuvée dans sa forme actuelle.';
    
    $this->emailService->sendEmail($to, $subject, $content);
    
    return $this->redirectToRoute('app_listecredit');
}

#[Route('/listecreditparid/{id_client}', name: 'app_recherchecreditid')]
public function listeacreditparid(CreditRepository $creditRepository, $id_client): Response
{
    $credit = $creditRepository->findOneBy(['id_client' => $id_client]);
      if ($credit === null) {
        // Handle the case where no credit is found
        // For example, you can redirect to an error page or display a message
        return $this->render('credit/not_found.html.twig');
    }

    return $this->render('credit/listecreditv.html.twig', ["credit" => $credit]);
}


#[Route('/graphique', name: 'app_graphique')]
public function statistiques(CreditRepository $creditRepository)
{
    $credits=$creditRepository->findAll();

    $credittaux=[];
    $creditmensualite=[];
    $creditcount=[];
    $status = [];
    $employee=0;
    $nonemployee=0;

    foreach($credits as $credit){
        $creditid[]=$credit->getId();
        $credittaux[]=$credit->getTaux();

        $creditmensualite[]=$credit->getMensualite();
        $creditmontant[]=$credit->getMontant();

        $datedebut[]=$credit->getdatedebut();
        $creditcount[]=count($credit->getRdv());
                $status[] = $credit->getStatusClient();
                

               

        

    }
    foreach ($status as $statu) {
        if ($statu === "employee") {
            $employee++; // Increment employee count
        } elseif ($statu === "non employee") {
            $nonemployee++; // Increment non-employee count
        }
    }



   return $this->render('credit/stats.html.twig',[
    'credittaux'=>json_encode($credittaux),
    'creditmontant'=>json_encode($creditmontant),
    'employee' => $employee,         // Directly pass the count
    'nonemployee' => $nonemployee,



    'datedebut'=>json_encode($datedebut),

    'creditmensualite'=>json_encode($creditmensualite),
    'creditcount'=>json_encode($creditcount),
    'creditid'=>json_encode($creditid)



   ]);   
}
#[Route('/listecredit/{min}/{max}', name: 'creditminmax')]
public function authersearchbybook(CreditRepository $creditRepository, $min, $max, Request $request): Response
{
    $credit = $creditRepository->searchcreditminmax($min, $max);
    return $this->render('credit/listecreditvv.html.twig', [
        'credit' => $credit,
    ]);
}

#[Route('/listecreditsorted', name: 'sortedlist')]
public function asortedcredit(CreditRepository $creditRepository): Response
{
    $credit = $creditRepository->findAllSortedByMontant();
    return $this->render('credit/sortedcreditd.html.twig', [
        'credit' => $credit,
    ]);
}




  
}
















