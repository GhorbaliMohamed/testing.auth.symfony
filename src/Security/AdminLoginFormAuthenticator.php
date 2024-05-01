<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AdminLoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_admin_login';
    private $UtilisateurRepository;
    private $urlGenerator;
    
    public function __construct(UrlGeneratorInterface $urlGenerator,        
        UtilisateurRepository $UtilisateurRepository
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->UtilisateurRepository = $UtilisateurRepository;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        $user = $this->UtilisateurRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Invalid email or password.');
        }
        if ($user->getRole() !== 'Admin') {
            throw new CustomUserMessageAuthenticationException('You are not authorized to access this application.');
        }
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
        
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        return new RedirectResponse('/mainadmin/dashboard');
        
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
