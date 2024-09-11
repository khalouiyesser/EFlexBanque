<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RdvRepository;
use App\Repository\UserRepository;

use App\Service\SmsGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SmsController extends AbstractController
{
   
    //La vue du formulaire d'envoie du sms
    #[Route('/s', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('sms/index.html.twig',['smsSent'=>false]);
    }

    //Gestion de l'envoie du sms
    #[Route('/sendSms', name: 'send_sms', methods: ['POST'])]
    public function sendSms(Request $request, SmsGenerator $smsGenerator, RdvRepository $rdvRepository, UserRepository $userRepository): Response
    {
        $id = $request->request->get('rdv_id');
        $rdv = $rdvRepository->find($id);
       
        $rdvs=$rdvRepository->findAll();
        $idd = $rdv->getIdClient();
        $user = $userRepository->find($idd);
        $number_test=$_ENV['twilio_to_number'];// Numéro vérifier par twilio. Un seul numéro autorisé pour la version de test.
        // Retrieve other form data
        $number = $request->request->get('number');
        $name = "our client ";
    $appointmentDate =  $rdv->getDateRdv()->format('Y-m-d') ;
    $text = "Hello $name, your appointment is scheduled for $appointmentDate.";
        // Send SMS
        $smsGenerator->sendSms($number_test, $name, $text);
    
        // Render the same page
        return $this->render('credit/listerdv.html.twig',["rdvs"=>$rdvs,'smsSent'=>true]);
    }
    
}