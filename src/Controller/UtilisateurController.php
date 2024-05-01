<?php

namespace App\Controller;
use App\Repository\UtilisateurRepository;

use App\Form\UtilisateurType;
use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\ChangePasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Doctrine\ORM\EntityManagerInterface;

class UtilisateurController extends AbstractController
{
    private $UtilisateurRepository;
    private $passwordHasher;

    public function __construct(UtilisateurRepository $UtilisateurRepository,UserPasswordHasherInterface $passwordHasher)
    {
        $this->UtilisateurRepository = $UtilisateurRepository;
        $this->passwordHasher = $passwordHasher;

    }

    #[Route(path: '/mainadmin/dashboard', name: 'app_admin_dashboard')]
    public function admindhashboard(): Response
    {
        return $this->render('back/dashboard.html.twig');
    }

    // AdminController.php
#[Route(path: '/mainadmin/settings', name: 'app_admin_settings')]
public function admindsettings(Request $request, EntityManagerInterface $entityManager, Security $security): Response
{
    /** @var Utilisateur $user */
    $user = $security->getUser();

    $form = $this->createFormBuilder($user)
        ->add('nom', TextType::class, [
            'label' => 'Nom',
        ])
        ->add('prenom', TextType::class, [
            'label' => 'Prénom',
        ])
        ->add('email', EmailType::class, [
            'label' => 'Email',
        ])
        ->add('telephone', TelType::class, [
            'label' => 'Téléphone',
        ])
        ->add('adresse', TextType::class, [
            'label' => 'Adresse',
        ])
        ->add('image', FileType::class, [
            'label' => 'Image de profil',
            'required' => false,
            'data_class' => null,
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $form->getData();
        
        /** @var UploadedFile|null $file */
        $file = $form->get('image')->getData();
        if ($file) {
            $fileName = $this->uploadFile($file);
            $user->setImage($fileName);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Votre compte a été mis à jour avec succès.');
        return $this->redirectToRoute('app_admin_settings');
    }

    return $this->render('back/settings.html.twig', [
        'form' => $form->createView(),
    ]);
}

private function uploadFile(UploadedFile $file): string
{
    $fileName = uniqid() . '.' . $file->guessExtension();
    $file->move($this->getParameter('uploads_dir'), $fileName);
    return $fileName;
}


    #[Route(path: '/mainadmin/changepassword', name: 'app_admin_password')]
    public function changePassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        /** @var Utilisateur $user */
        $user = $this->getUser();
    
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('oldPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();
    
            if ($userPasswordHasher->isPasswordValid($user, $oldPassword)) {
                $user->setPassword($userPasswordHasher->hashPassword($user, $newPassword));
                $entityManager->flush();
                $this->addFlash('success', 'Your password has been updated successfully.');
                return $this->redirectToRoute('app_admin_settings');
            } else {
                $this->addFlash('error', 'The old password is incorrect.');
            }
        }
    
        return $this->render('back/changepassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route(path: '/mainadmin/Profile', name: 'app_admin_profile')]
    public function admindprofile(): Response
    {
        return $this->render('back/profile.html.twig');
    }
    #[Route(path: '/mainadmin/Users', name: 'app_admin_users')]
    public function admindusers(Request $request): Response
    {
        $orderBy = $request->query->get('orderBy', 'id');
        $orderDirection = $request->query->get('orderDirection', 'ASC');
        $searchQuery = $request->query->get('search');
    
        $users = $this->UtilisateurRepository->findAllOrdered($searchQuery, $orderBy, $orderDirection);
    
        return $this->render('back/users.html.twig', [
            'users' => $users,
            'orderBy' => $orderBy,
            'orderDirection' => $orderDirection,
            'searchQuery' => $searchQuery,
        ]);
    }

    #[Route(path: '/mainadmin/users/delete/{id}', name: 'app_admin_user_delete')]
    public function deleteUser(Request $request, UtilisateurRepository $utilisateurRepository, $id): Response
    {
        $user = $utilisateurRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

            $utilisateurRepository->delete($user);
            $this->addFlash('success', 'User deleted successfully');


        return $this->redirectToRoute('app_admin_users');
    }

    #[Route(path: '/mainadmin/users/edit/{id}', name: 'app_admin_user_edit')]
    public function editUser(Request $request, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $entityManager, $id): Response
    {
        $user = $utilisateurRepository->find($id);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
    
        $form = $this->createFormBuilder($user)
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('telephone')
            ->add('adresse')
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'User updated successfully');
            return $this->redirectToRoute('app_admin_users');
        }
    
        return $this->render('back/user_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
