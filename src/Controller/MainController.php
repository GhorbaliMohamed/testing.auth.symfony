<?php

namespace App\Controller;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main', methods: ['GET', 'POST'])]
    public function index( Request $request): Response
    {   
        $recaptcha = new ReCaptcha('6LegF80pAAAAAC3C9jou_NWN06_Gc1awIjpDx4te');
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
        return $this->render('front/index.html.twig', [
            'recaptcha_site_key' => '6LegF80pAAAAAC_qwlwNqZ5k2xNt9dZP2JrFameb',
        ]);
    }
    #[Route(path: '/user/Profile', name: 'app_user_profile')]
    public function profile(): Response
    {
        return $this->render('front/profile.html.twig');
    }
}
