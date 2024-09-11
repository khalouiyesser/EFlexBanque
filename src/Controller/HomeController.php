<?php

namespace App\Controller;

//use OpenAI;
use App\Entity\Demandestage;
use App\Entity\User;
use App\Repository\CompteRepository;
use App\Repository\DemandeStageRepository;
use App\Repository\OffreStageRepository;
use App\Repository\UserRepository;
use App\Service\AnalyseCv;
use App\Service\Mailing;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController
{
    public Mailing $emailService;
    public string $directory = 'uploads_directory';
    public function __construct(Mailing $emailService)
    {
        $this->emailService = $emailService;
    }
    #[Route('/', name: 'app_home')]
    public function index(?string $question, ?string $response): Response
    {
        return $this->render('home/index.html.twig', [
            'question' => $question,
            'response' => $response
        ]);
    }
    #[Route('/Shayma/{id}', name: 'Shayma', methods:["POST", "GET"])]
    public function Shayma($id, ManagerRegistry $managerRegistry, CompteRepository $compteRepository): JsonResponse
    {
        $compte = $compteRepository->find($id);
        $user = new User();
        $subject = 'Acceptation de création du compte';
        try {
            $lastFourDigits = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            $constantDigits = '1234';
            $rib = $constantDigits . $lastFourDigits;
            $randomBytes = random_bytes(4);
            $randomString = substr(bin2hex($randomBytes), 0, 8);
            
            // Hasher le mot de passe
            $hashedPassword = password_hash($randomString, PASSWORD_DEFAULT);
            
            $user->setPassword($hashedPassword);
            $user->setRib($rib);
            $user->setEmail($compte->getEmail());
//            dd($compte->getNom()." ".$compte->getPrenom());
            $user->setName($compte->getNom()." ".$compte->getPrenom());
            $user->setRoles(["ROLE_CLIENT"]);
            $user->setIsBlocked(0);
            $user->setIsVerified(1);
            $user->setTel($compte->getNumeroTelephone());
            
            $em = $managerRegistry->getManager();
            $em->persist($user);
            $em->flush();
            
            $code = "<h1>Votre demande a été acceptée!</h1><br>
            <p>Votre Rib est : {$rib}</p><br>
            <p>Votre Compte est: </p><br>
            <p>Email : {$compte->getEmail()}</p><br>
            <p>Password : {$randomString}</p><br>
            <p>Cordialement.</p>";
        } catch (\Exception $e) {
            // Gérer les exceptions ici
        }
        
        $this->emailService->sendEmailShayma($compte->getEmail(), $subject, $code);
        
        $data = [
            'message' => 'Ceci est un exemple de réponse depuis Symfony',
            'score' => 1
        ];
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
    
    
    #[Route('/AnalyseurCvApi/{id}/{cv}', name: 'AnalyseurCvApi', methods:["POST", "GET"])]
    public function AnalyseurCvApi($id, $cv, Request $request, Mailing $mailing, OffreStageRepository $offreStageRepository, AnalyseCv $analyseCv): JsonResponse
    {
        $offre = $offreStageRepository->find($id);
        $cheminFichier = $this->getParameter('uploads_directory').'/'.$cv;
        $score = $analyseCv->analyseCV($cheminFichier, $offre->getMotsCles());
        
        $mailing->sendEmail("khaluiyesser@gmail.com", "test API", "il marche bien");
        
        // Création du tableau contenant le message et le score
        $data = [
            'message' => 'Ceci est un exemple de réponse depuis Symfony',
            'score' => $score
        ];
        
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
   
    #[Route('/mailingRefuser/{email}/{id}', name: 'mailingRefuser', methods:["POST", "GET"])]
    public function mailingRefuser($email,$id,DemandeStageRepository $demandeStageRepository): JsonResponse
    {
        $demande = $demandeStageRepository->find($id);
        $subject = "Malheuresement";
        $nom = $demande->getNom();
        $html ="<div>Bonjour {$nom}.<br>Votre Demande a été réfusé .<br>";
        $this->emailService->sendEmail($email,$subject,$html);
        $data = [
            'message' => 'Ceci est un exemple de réponse depuis Symfony',
            'score' => 2
        ];
        
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
//    #[Route('/mailingApprouver/{email}/{id}', name: 'mailingApprouver', methods:["POST", "GET"])]
//    public function mailingApprouver($email,$id,DemandeStageRepository $demandeStageRepository): JsonResponse
//    {
//        $demande = $demandeStageRepository->find($id);
//        $subject = "Félécitations";
//        $nom = $demande->getNom();
//        $html ="<div>Bonjour {$nom}.<br>Félécitations votre demande est accepter .<br>";
//        $this->emailService->sendEmail($email,$subject,$html);
//        $data = [
//            'message' => 'Ceci est un exemple de réponse depuis Symfony',
//            'score' => 1
//        ];
//
//        return new JsonResponse($data, JsonResponse::HTTP_OK);
//    }
    #[Route('/Mailing/{email}', name: 'mailing', methods:["POST", "GET"])]
    public function mailing($email,DemandeStageRepository $demandeStageRepository): JsonResponse
    {
//        $demande = $demandeStageRepository->find($id);
        $subject = "Félécitations";
//        $nom = $demande->getNom();
        $html ="<div>Bonjour {$email}.<br>Félécitations votre demande est accepter .<br>";
        
        $this->emailService->sendEmail($email,$subject,$html);
        $data = [
            'message' => 'Ceci est un exemple de réponse depuis Symfony',
            'score' => 1
        ];
        
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
    #[Route('/MdpOublieA/{email}/{code}', name: 'MdpOublieA', methods:["POST", "GET"])]
    public function MdpOublieA($email,$code): JsonResponse
    {
            $subject = 'Réinitialisation du mot de passe';
//            $html = 'Votre nouveau mot de passe est : ' . $newPassword;
            $this->emailService->sendEmail($email, $subject, $code);
//
//            // $mailer->send($email);
            $data = [
                'message' => 'Ceci est un exemple de réponse depuis Symfony',
                'score' => 1
            ];
//
//        }
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
    
}
