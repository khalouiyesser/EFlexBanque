<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotpasswordType;
use App\Repository\UserRepository;
use App\Service\Mailing;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    public Mailing $emailService;
    public function __construct(Mailing $emailService)
    {
        $this->emailService = $emailService;
    }
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: '/loginClient', name: 'app_loginClient')]
    public function loginClient(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/loginClient.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logoutClient', name: 'app_logoutClient')]
    public function logoutClient(): void
    {
        
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    
    
    #[Route(path: '/mdpoublie', name: 'mdpoublie')]
    public function motdepasseoublie(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer)
    {
        $form = $this->createForm(ForgotpasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $user = $userRepository->findOneBy(['email' => $formData['email']]);
            
            if ($user) {
                $newPassword = bin2hex(random_bytes(4));
                
                $encodedPassword = $passwordEncoder->encodePassword($user, $newPassword);
                $user->setPassword($encodedPassword);
                $entityManager->persist($user);
                
                
                $entityManager->flush();
                
//                $email = (new Email())
                    
                    $to = $user->getEmail();
                    $subject ='Réinitialisation du mot de passe';
                    $html = 'Votre nouveau mot de passe est : ' . $newPassword;
                    $this->emailService->sendEmail($to,$subject,$html);
                
               // $mailer->send($email);
            }
            return $this->redirectToRoute('app_loginClient');
        }
        
        return $this->render('security/forgotpass.html.twig', [
            'form' => $form->createView(),
        ]);
    }



//    #[Route(path:'/forgot', name:'forgot')]
//    public function forgotPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer, ManagerRegistry $managerRegistry, TokenGeneratorInterface $tokenGenerator)
//    {
//        $form = $this->createForm(ForgotPasswordType::class);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $donnees = $form->getData();
//            $email = $donnees['email'];
//
//            $existingUser = $userRepository->findOneBy(['email' => $email]);
//
//            if (!$existingUser) {
//                $this->addFlash('danger', 'Cette adresse n\'existe pas');
//                return $this->redirectToRoute("forgot");
//            }
//
//            $token = $tokenGenerator->generateToken();
//
//            try {
//                $existingUser->setResetToken($token);
//                $entityManager = $managerRegistry->getManager();
//                $entityManager->persist($existingUser);
//                $entityManager->flush();
//            } catch (\Exception $exception) {
//                $this->addFlash('warning', 'Une erreur est survenue :' . $exception->getMessage());
//                return $this->redirectToRoute("app_login");
//            }
//
//            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
//
//            $email = (new Email())
//                ->from('yesser.khaloui@etudiant-fst.utm.tn')
//                $to = $existingUser['email']; // Utilisation de la clé 'email' du tableau
//               $subject = 'Mot de passe oublié';
//                $html = "<p>Bonjour</p> Une demande de réinitialisation de mot de passe a été effectuée. Veuillez cliquer sur le lien suivant : $url";
//                  $this->emailService->sendEmail($to,$subject,$html);
//            $mailer->send($email);
//
//            $this->addFlash('message', 'E-mail de réinitialisation du mot de passe envoyé.');
//
//            return $this->redirectToRoute("app_login");
//        }
//
//        return $this->render("reset_password/request.html.twig",['form'=>$form->createView()]);
//    }
//
//
//
//
//    #[Route(path:'/resetpassword/{token}', name:'app_reset_password')]
//
//    public function resetpassword(Request $request,string $token, UserPasswordHasherInterface $userPasswordHasher,ManagerRegistry $managerRegistry)
//    {
//        $user = $this->$managerRegistry->getRepository(User::class)->findOneBy(['reset_token'=>$token]);
//
//        if($user == null ) {
//            $this->addFlash('danger','TOKEN INCONNU');
//            return $this->redirectToRoute("app_login");
//
//        }
//
//        if($request->isMethod('POST')) {
//            $user->setResetToken(null);
//
//            $user->setPassword(
//                $userPasswordHasher->hashPassword($user,$request->request->get('password')));
//            $entityManger = $this->$managerRegistry->getManager();
//            $entityManger->persist($user);
//            $entityManger->flush();
//
//            $this->addFlash('message','Mot de passe mis à jour :');
//            return $this->redirectToRoute("app_login");
//
//        }
//        else {
//            return $this->render("reset_password/request.html.twig",['token'=>$token]);
//
//        }
//    }



}
