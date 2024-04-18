<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('password', PasswordType::class, [
            'label' => 'Old Password',
            'constraints' => [
                new NotBlank(['message' => 'Please enter your old password']),
            ],
        ])
        ->add('password', PasswordType::class, [
            'label' => 'New Password',
            'constraints' => [
                new NotBlank(['message' => 'Please enter a new password']),
                new Length(['min' => 6, 'minMessage' => 'Your password should be at least {{ limit }} characters']),
            ],
        ])
        ->add('password', PasswordType::class, [
            'label' => 'Confirm New Password',
            'constraints' => [
                new NotBlank(['message' => 'Please confirm your new password']),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
