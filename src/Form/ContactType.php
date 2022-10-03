<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                    'attr' => array(
                        'placeholder' => 'Votre nom et prénom'
                    ),
                    'required' => true,
                    'constraints' => [new NotBlank(['message' => 'ne peut pas être vide'])]
                ]
            )
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
            ->add('content', TextareaType::class, [
                'attr' => array(
                    'placeholder' => 'Contenu de votre demande'
                ),
                'required' => true,
                'constraints' => [new NotBlank(['message' => 'ne peut pas être vide'])]

            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Envoyer le message',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
