<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class EditPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
        ->add('currentPassword', PasswordType::class, [
            "mapped" => false,
            "constraints" => [
                new UserPassword([
                    "message" => "Ceci n'est pas votre mot de passe actuel"
                ])
            ]
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            
            'first_options' => [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs est obligatoire',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => "/^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ỳ])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ỳ0-9]).{11,255}$/",
                        'match'=> true,
                        'message'=> "Le mot de passe doit contentir au moins une lettre miniscule, majuscule, un chiffre et un caractère spécial.",
                    ]),
                    
                    new NotCompromisedPassword(),
                ],
                'label' => 'New password',
            ],
            'second_options' => [
                'label' => 'Repeat Password',
            ],
            'invalid_message' => 'Les mots de passe doivent être identique',
            // Instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
        ])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            
        ]);
    }
}
