<?php
namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Profile',
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}