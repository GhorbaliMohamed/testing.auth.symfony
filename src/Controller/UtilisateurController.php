<?php

namespace App\Controller;
use App\Repository\UtilisateurRepository;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\ChangePasswordType;
class UtilisateurController extends AbstractController
{
    private $UtilisateurRepository;
    private $passwordHasher;

    public function __construct(UtilisateurRepository $UtilisateurRepository,UserPasswordHasherInterface $passwordHasher)
    {
        $this->UtilisateurRepository = $UtilisateurRepository;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route(path: '/admin/dashboard', name: 'app_admin_dashboard')]
    public function admindhashboard(): Response
    {
        return $this->render('back/dashboard.html.twig');
    }
    #[Route(path: '/admin/settings', name: 'app_admin_settings')]
    public function admindsettings(): Response
    {
        return $this->render('back/settings.html.twig');
    }



    #[Route(path: '/admin/changepassword', name: 'app_admin_password')]
    public function admindpassword(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Verify the old password
            if (!$this->passwordHasher->isPasswordValid($user, $form->get('oldPassword')->getData())) {
                $this->addFlash('error', 'Old password is incorrect.');
                return $this->render('back/changepassword.html.twig', [
                    'changePasswordForm' => $form->createView(),
                ]);
            }
    
            // Hash the new password and update the user
            $newEncodedPassword = $this->passwordHasher->hashPassword(
                $user,
                $form->get('newPassword')->getData()
            );
            $user->setPassword($newEncodedPassword);
            $this->UtilisateurRepository->save($user);
    
            $this->addFlash('success', 'Password changed successfully!');
            return $this->redirectToRoute('app_admin_dashboard');
        }
    
        return $this->render('back/changepassword.html.twig', [
            'changePasswordForm' => $form->createView(),
        ]);
    }



    #[Route(path: '/admin/Profile', name: 'app_admin_profile')]
    public function admindprofile(): Response
    {
        return $this->render('back/profile.html.twig');
    }
    #[Route(path: '/admin/Users', name: 'app_admin_users')]
    public function admindusers(Request $request): Response
    {
        $orderBy = $request->query->get('orderBy', 'id');
        $orderDirection = $request->query->get('orderDirection', 'ASC');

        $users = $this->UtilisateurRepository->findAllOrdered($orderBy, $orderDirection);
        return $this->render('back/users.html.twig', [
            'users' => $users,
            'orderBy' => $orderBy,
            'orderDirection' => $orderDirection,
        ]);
    }




}
