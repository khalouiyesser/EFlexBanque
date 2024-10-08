<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\User\UserInterface;
class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    public const LOGIN_CLIENT_ROUTE = 'app_loginClient';
    private $userReposetory;

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
       $this->userReposetory = UserRepository::class;
    }

    public function authenticate(Request $request): Passport
    {
        
        $email = $request->request->get('email', '');
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $token->getUser();
        if ($user->isBlocked()) {
            // Rediriger l'utilisateur vers une page d'erreur ou afficher un message d'erreur
            return new Response("is blocked");
        }else {
            if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
                return new RedirectResponse($targetPath);
            }
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                return new RedirectResponse($this->urlGenerator->generate('app_dashbord_admin'));
                
            } elseif (in_array('ROLE_EMPLOYEE', $user->getRoles())) {
                return new RedirectResponse($this->urlGenerator->generate('app_dashbordEmploye'));
            } elseif (in_array('ROLE_CLIENT', $user->getRoles())) {
                return new RedirectResponse($this->urlGenerator->generate('app_dashbordClient', ['user' => $user->getUserIdentifier()]));
            }
            
            
            // For example:
            return new RedirectResponse($this->urlGenerator->generate('app_actualite'));
            throw new \Exception('TODO: provide a valid redirect inside ' . __FILE__);
        }
    }

    protected function getLoginUrl(Request $request): string
    {
           // Utilisez la constante appropriée pour générer l'URL de connexion
           if ($request->attributes->get('_route') === self::LOGIN_CLIENT_ROUTE) {
            return $this->urlGenerator->generate(self::LOGIN_CLIENT_ROUTE);
        } else {
            return $this->urlGenerator->generate(self::LOGIN_ROUTE);
        }
    }
    public function checkCredentials($credentials, UserInterface $user)
    {
        // Vérifier si l'utilisateur est bloqué
        if ($user->isBlocked()) {
            throw new CustomUserMessageAuthenticationException('Votre compte a été bloqué.');
        }

        // Votre logique de vérification des identifiants ici
    }

}


