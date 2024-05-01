<?php
namespace App\Controller;


use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class RegistrationController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request)
    {
        $user = new Utilisateur();
        $user->setRole("User");
        $form = $this->createForm(UtilisateurType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPassword = $user->getPassword();
            // Encode the new users password
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            // Set their role
            

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}