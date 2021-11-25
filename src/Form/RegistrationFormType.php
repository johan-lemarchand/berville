<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => array(
                    'placeholder' => 'Votre email'
                ),
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'ne peut pas être vide']),
                    new Email(['message' => 'l\'adresse mail n\'as pas le bon format'])
                ]

            ])
            ->add('firstname', TextType::class, [
                    'attr' => array(
                        'placeholder' => 'Votre nom'
                    ),
                    'required' => true,
                    'constraints' => [new NotBlank(['message' => 'ne peut pas être vide'])]

                ]
            )
            ->add('lastname', TextType::class, [
                'attr' => array(
                    'placeholder' => 'Votre prénom'
                ),
                'required' => true,
                'constraints' => [new NotBlank(['message' => 'ne peut pas être vide'])]

            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'veuillez entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'S\'inscrire',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
