<?php

namespace App\Controller;
use App\Entity\Cheque;
use App\Entity\Compte;
use App\Entity\User;
use App\Form\ChequeType;
use App\Repository\ChequeRepository;
use App\Repository\CompteRepository;
use App\Repository\UserRepository;
use App\Service\uploadFile;
use App\Service\YousignService;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
// use function MongoDB\BSON\toRelaxedExtendedJSON;
use Symfony\Component\HttpClient\Response\TraceableResponse;
use App\Service\TwilioSmsService;


class ChequeController extends AbstractController

{
    
    //public Mailing $emailService;
    public string $directory = 'uploads_directory';
    //public function __construct(Mailing $emailService)
    // {
    //    $this->emailService = $emailService;
    // }
    /*Client*/
    
    
    /* #[Route('/addcheques', name: 'addcheques')]
        public function addcheques(ChequeRepository $chequeRepository, Request $request, ManagerRegistry $managerRegistry , SluggerInterface $slugger , UploadedFile $uploadedFile): Response
        {
            $cheque = new Cheque();
            $form = $this->createForm(ChequeType::class, $cheque);
            $em = $managerRegistry->getManager();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($cheque);
                $em->flush();
                return $this->redirectToRoute('historique');
            }

            return $this->render('frontoffice/Client/cheque/add.html.twig', [
                'form' => $form->createView()
            ]);
        } */
    
    #[Route('/historique', name: 'historique')]
    public function historique(ChequeRepository $chequeRepository):Response
    {
        $liste= $chequeRepository->findAll();
        return $this->render('frontOffice/Client/cheque/historique.html.twig',[
            'cheques'=>$liste,
        ]);
    }
    
    #[Route('/addcheques', name: 'addcheques')]
    public function addcheques(Request $request, ManagerRegistry $managerRegistry, SluggerInterface $slugger, uploadFile $uploadFile,UserRepository $repository,CompteRepository $chequeRepository): Response
    {
        $cheque = new Cheque();
        $user = $this->getUser();
        $cheque->setUser($user);
        $cheque->setRib($user->getRib());
        $compte = $chequeRepository->findOneBySomeField($user->getRib());
        $cheque->setCompte($compte);
        $cheque->setUser($user);
        
        $form = $this->createForm(ChequeType::class, $cheque);
        $em = $managerRegistry->getManager();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photoCin')->getData();
            $photoCin = $uploadFile->uploadFile($photo);
            $cheque->setPhotoCin($photoCin);
            $cheque->setDecision("encours");
            $em->persist($cheque);
            $em->flush();
            return $this->redirectToRoute('historique');
        }
        
        return $this->render('frontOffice/Client/cheque/add.html.twig', [
            'Cheque' => $cheque,
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/AfficherDemande', name: 'AfficherDemande')]
    public function AfficherDemande(ChequeRepository $chequeRepository):Response
    
    {
        $liste= $chequeRepository->HistoriqueDesCheques('encours');
        return $this->render('backoffice/admin/cheque/list.html.twig',[
            'cheques'=>$liste,
        ]);
    }
    
    #[Route('/showListeCheque', name: 'showListeCheque')]
    public function showListeCheque(ChequeRepository $chequeRepository):Response
    {
        $cheque= $chequeRepository->listeDesChequesAccepte('Approuvé');
        return $this->render('backoffice/admin/cheque/historiqueAdmin.html.twig',[
            'cheques'=>$cheque,
        ]);
    }
    #[Route('/AfficherDemandeE', name: 'AfficherDemandeE')]
    public function AfficherDemandeE(ChequeRepository $chequeRepository):Response
    
    {
        $liste= $chequeRepository->listeDesChequesAccepte('encours');
        return $this->render('backoffice/Employe/cheque/listE.html.twig',[
            'cheques'=>$liste,
        ]);
    }
    
    #[Route('/showListeChequeE', name: 'showListeChequeE')]
    public function showListeChequeE(ChequeRepository $chequeRepository):Response
    
    {
        $cheque= $chequeRepository->HistoriqueDesCheques('Approuvé');
        return $this->render('backoffice/Employe/cheque/listCheque.html.twig',[
            'cheques'=>$cheque,
        ]);
    }
    #[Route('/chatN', name: 'chatN')]
    public function chatN():Response
    
    {
        
        
        $client = new \GuzzleHttp\Client();
        
        $response = $client->request('POST', 'https://custom-chatbot-api.p.rapidapi.com/chatbotapi', [
                    'body' => '{
            "bot_id": "OEXJ8qFp5E5AwRwymfPts90vrHnmr8yZgNE171101852010w2S0bCtN3THp448W7kDSfyTf3OpW5TUVefz",
            "messages": [
                {
                    "role": "user",
                    "content": "ou se trouve la tunisie"
                }
            ],
            "user_id": "",
            "temperature": 0.9,
            "top_k": 5,
            "top_p": 0.9,
            "max_tokens": 256,
            "model": "matag2.0"
                 }',
                    'headers' => [
                        'X-RapidAPI-Host' => 'custom-chatbot-api.p.rapidapi.com',
                        'X-RapidAPI-Key' => '6335f0e1b6mshe51bdb6be5175a1p1914fajsn7770537fdb84',
                        'content-type' => 'application/json',
                    ],
                ]);
        
//        echo $response->getBody();
        return $this->render('backoffice/Employe/cheque/chat.html.twig',[
            'response'=>$response->getBody(),
        ]);
    }
    
    
    
    #[Route('/pdfCheque/{id}', name: 'pdfCheque')]
    public function pdfCheque($id, Request $request, Cheque $cheque ,ChequeRepository $chequeRepository): Response
    
    {
        $cheque = $chequeRepository->find($id);
        $dompdf = new Dompdf();
        $html = $this->renderView('frontOffice/Client/cheque/pdf.html.twig', [
            'i' => $cheque,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $output = $dompdf->output();
        $filename = 'cheque_' . $cheque->getId() . '.pdf';
        $file = $this->getParameter('kernel.project_dir') . '/public/' . $filename;
        
        $cheque->setpdfSansSignature($filename);
        $chequeRepository->save($cheque , true);
        
        file_put_contents($file, $output);
        return $this->redirectToRoute('showCheque', ['id' => $cheque->getId()]);
        //return $this->render('frontoffice/Client/cheque/pdf.html.twig', [
        
        //   'i' => $cheque,
        // ]);
        
    }
    
    #[Route('/showCheque/{id}', name: 'showCheque')]
    public function showCheque( $id, ChequeRepository $chequeRepository):Response
    
    {
        $liste= $chequeRepository->find($id);
        return $this->render('frontOffice/Client/cheque/show.html.twig',[
            'i'=>$liste,
        ]);
    }
    
    
    
    #[Route('/signature/{id}', name: 'signature')]
    public function signature($id, ChequeRepository $chequeRepository,YousignService $yousignService):Response
    {
        $cheque = $chequeRepository->find($id);
        //1 création de la demande de signature
        $yousignSignatureRequest = $yousignService->signatureRequest();
        $cheque->setSignatureId($yousignSignatureRequest['id']);
        $chequeRepository->save($cheque, true);
//        dd($cheque->getPdfSansSignature());
        
        
        
        //2 upload du document
        $uploadDocument = $yousignService->addDocumentToSignatureRequest($cheque->getSignatureId(), "cheque_1.pdf" );
        $cheque->setDocumentId($uploadDocument['id']);
        $chequeRepository->save($cheque, true);
        
        //3 ajout des signataires
        $signerId = $yousignService->addSignerToSignatureRequest(
            $cheque->getSignatureId(),
            $cheque->getDocumentId(),
            $cheque->getEmail(),
            $cheque->getNomPrenom(),
        );
        
        $cheque->setSignerId($signerId['id']);
        $chequeRepository->save($cheque,true);
        
        
        //4 Envoi de la demande de signature
        $yousignService->activateSignatureRequest($cheque->getSignatureId());
        
        return $this->redirectToRoute('app_constat_show', ['id' => $cheque->getId()], Response::HTTP_SEE_OTHER);
    
    
    
    
    
    /* // $cheque = $chequeRepository->find($id);

      $cheminFichier = $this->getParameter('uploads_directory').'/'.$cheque->getPdfSansSignature();
      $yousignSignatureRequest= $yousignService->signatureRequest();
      $filename = $cheque->getPdfSansSignature();
      $id = $yousignSignatureRequest['id'];

      $cheque->setSignerId($id);
      $chequeRepository->save ($cheque , true);

      $uploadDocument=$yousignService->addDocumentToSignatureRequest($id , $filename );

     // $cheque->getPdfSansSignature();
     // $cheque->getSignatureId();
       $yesser = $cheque->setDocumentId($uploadDocument['id']);
      $chequeRepository->save ($cheque , true);

      $signerId=$yousignService->addSigner(
          $cheque->getSignerId(),
          $cheque->getDocumentId(),
          $cheque->getEmail(),
          $cheque->getNomPrenom()
      );
      $cheque->setSignerId($signerId['id']);
      $chequeRepository->save($cheque , true);

      $yousignService->activateSignatureRequest($cheque->getSignatureId());
      return $this->redirectToRoute('showCheque', ['id' => $cheque-> getId ()] , Response:: HTTP_SEE_OTHER);

*/
//        $cheque = $chequeRepository->find($id);
//        $yousignSignatureRequest = $yousignService->signatureRequest();
//        $cheque->setSignatureId($yousignSignatureRequest['id']);
//        $chequeRepository->save($cheque, true);
////        dd($cheque);
////        $cheque->setPdfSansSignature()
//        $uploadDocument = $yousignService->addDocumentToSignatureRequest($cheque->getSignatureId(), $cheque->getPdfSansSignature() );
//        $cheque->setDocumentId($uploadDocument['id']);
//        $chequeRepository->save($cheque, true);
//        $signerId = $yousignService->addDocumentToSignatureRequest(
//            $cheque->getSignatureId(),
//            $cheque->getDocumentId(),
//        );
////        dd($signerId);
//
//        $cheque->setSignerId($signerId['id']);
//        $chequeRepository->save($cheque,true);
//        $yousignService->activateSignatureRequest($cheque->getSignatureId());
//        return $this->redirectToRoute('showCheque', ['id' => $cheque-> getId ()] , Response:: HTTP_SEE_OTHER);
    }
    
    
    
    
    #[Route('/deleteDemandeChequeClient/{id}', name: 'deleteDemandeChequeClient')]
    public function deleteDemandeChequeClient($id, ManagerRegistry $managerRegistry, ChequeRepository $chequeRepository):Response
    {
        $emm=$managerRegistry->getManager();
        $idremove=$chequeRepository->find($id);
        $emm->remove($idremove);
        $emm->flush();
        return $this->redirectToRoute('historique');
        
        
    }
    
    
    /*admin*/
    
    
    
    #[Route('/ApprouverCheque/{id}', name: 'ApprouverCheque')]
    public function ApprouverCheque($id, ManagerRegistry $managerRegistry, ChequeRepository $chequeRepository ,TwilioSmsService $twilioSmsService):Response
    {
        $cheque=$chequeRepository->find($id);
        $cheque->setDecision("Approuvé");
        $body = "'Bonjour ' . ', ' .
                    'Nous sommes heureux de vous informer que votre demande de cheque' .
                    'a été approuvée avec succès. ' .
                    'Cordialement, [ EFB]'";
        $chequeRepository->sms('+21658911742',$body);
        $emm=$managerRegistry->getManager();
        $emm->persist($cheque);
        $emm->flush();
        //$to=$cheque->getEmail();
        //$nometprenom=$cheque->getNomPrenom();
        //$subject="Félicitations";
        //$html="<div> Salut {$nometprenom}.<br>votre demande a été accepté .<br>";
        //$this->emailService->sendEmail($to,$subject,$html);
        return  $this->redirectToRoute('showListeCheque');
    }
    
    
    #[Route('/deleteDemandeCheque/{id}', name: 'deleteDemandeCheque')]
    public function deleteDemandeCheque($id,ManagerRegistry $managerRegistry,ChequeRepository $repository):Response
    {
        $emm=$managerRegistry->getManager();
        $idremove=$repository->find($id);
        $body = "Bonjour ' . ', ' .
                    'Nous vous informons  que votre demande de cheque' .
                    'a été réfusée  . ' .
                    'Cordialement, [ EFB]";
        $repository->sms('+21658911742',$body);
        $emm->remove($idremove);
        $emm->flush();
        return $this->redirectToRoute('AfficherDemande');
        
        
    }
    
    
    
    /*Employe*/
    
    
    
    #[Route('/ApprouverChequeE/{id}', name: 'ApprouverChequeE')]
    public function ApprouverChequeE($id, ManagerRegistry $managerRegistry, ChequeRepository $chequeRepository , TwilioSmsService $twilioSmsService):Response
    {
        $cheque=$chequeRepository->find($id);
        $cheque->setDecision("Approuve");
        $body = "Bonjour ' . ', ' .
                    'Nous sommes heureux de vous informer que votre demande de cheque' .
                    'a été approuvée avec succès. ' .
                    'Cordialement, [ EFB]";
        $chequeRepository->sms('+21658911742',$body);
        $emm=$managerRegistry->getManager();
        $emm->persist($cheque);
        $emm->flush();
        return  $this->redirectToRoute('showListeChequeE');
    }
    #[Route('/deleteDemandeChequeEmp/{id}', name: 'deleteDemandeChequeEmp', methods: ['GET','POST'])]
    public function deleteDemandeChequeEmp($id,ManagerRegistry $managerRegistry,ChequeRepository $repository):Response
    {
        $emm=$managerRegistry->getManager();
        $idremove=$repository->find($id);
        $emm->remove($idremove);
        $emm->flush();
        return $this->redirectToRoute('AfficherDemandeE');
        
    }
    
    
    #[Route('/modifierCheque/{id}', name: 'modifierCheque')]
    public function modifierCheque($id,ManagerRegistry $managerRegistry,ChequeRepository $chequeRepository , Request $request):Response
    {
        $em=$managerRegistry->getManager();
        $idData =$chequeRepository->find($id);
        $form=$this->createForm(ChequeType::class,$idData);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $em->persist($idData);
            $em->flush();
            return $this->redirectToRoute('historique');
            
        }
        return $this->renderForm('frontOffice/Client/cheque/add.html.twig',[
            'form' => $form
        ]);
        
    }
    
    #[Route('/listeChequeParCompte/{compte}', name: 'listeChequeParCompte')]
    public function listeChequeParCompte($id,ChequeRepository $chequeRepository , Request $request):Response
    {
        
        $idData =$chequeRepository->chequeParClient($id);
        return $this->renderForm('frontOffice/Client/cheque/add.html.twig',[
            'liste' => $idData
        ]);
    }
//    #[Route('/statistiques', name: 'statistiques')]
//    public function statistiques(EntityManagerInterface $entityManager): Response
//    {
//        $query = "SELECT type_virement, COUNT(*) AS count FROM virements GROUP BY type_virement";
//        $statement = $entityManager->getConnection()->prepare($query);
//        $statement->execute();
//
//        $resultats = $statement->fetchAll();
//
//        // Convertir les résultats en format utilisable pour le diagramme (par exemple, JSON)
//        $data = [];
//        foreach ($resultats as $resultat) {
//            $data[$resultat['type_virement']] = $resultat['count'];
//        }
//
//        return $this->render('frontOffice/Client/dashboard.html.twig', [
//            'data' => $data, // Passer la variable data au template Twig
//        ]);
//    }

    
    
    
}