<?php

namespace App\Controller;
use ReCaptcha\ReCaptcha;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route(path: '/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
        $recaptcha = new ReCaptcha('6LegF80pAAAAAC_qwlwNqZ5k2xNt9dZP2JrFameb');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastEmail = $authenticationUtils->getLastUsername();
        if ($request->isMethod('POST')) {
            $recaptchaResponse = $request->request->get('g-recaptcha-response');
            $recaptchaResult = $recaptcha->verify($recaptchaResponse, $request->getClientIp());
            if ($recaptchaResult->isSuccess()) {
                // reCAPTCHA validation passed
                return $this->redirectToRoute('homepage');
            } else {
                // reCAPTCHA validation failed
                $this->addFlash('error', 'reCAPTCHA validation failed.');
            }
        }
        return $this->render('security/userlogin.html.twig', ['last_email' => $lastEmail, 'error' => $error
        ,'recaptcha_site_key' => '6LegF80pAAAAAC_qwlwNqZ5k2xNt9dZP2JrFameb']);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}