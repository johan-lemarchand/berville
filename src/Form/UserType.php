<?php

namespace App\Form;

use App\Entity\AvatarFile;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => array(
                    'placeholder' => 'Email de l\'utilisateur'
                ),
                'required' => true,
                'constraints' => [new NotBlank(['message' => 'ne peut pas être vide']), new Email(['message' => 'Votre email n\'est pas valide'])]
            ])
            /*->add('role', null, [
                'expanded' => true,
            ])*/
            ->add('password', PasswordType::class, [
                'mapped' => false,
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
            ->add('firstname', TextType::class, [
                'attr' => array(
                    'placeholder' => 'Nom de l\'utilisateur'
                ),
                'required' => true,
                'constraints' => [new NotBlank(['message' => 'ne peut pas être vide'])]
            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
                'required' => false,
            ])
            ->add('address', TextType::class, [
                'attr' => array(
                    'placeholder' => 'Adresse de l\'utilisateur'
                ),
                'required' => false,
            ])
            ->add('avatar', VichImageType::class,[
                'required' => false,
            ])
            ->add('license', TextType::class, [
                'attr' => array(
                    'placeholder' => 'License de l\'utilisateur'
                ),
                'required' => false,
            ])
            ->add('lastname', TextType::class, [
                'attr' => array(
                    'placeholder' => 'Prénom de l\'utilisateur'
                ),
                'required' => true,
                'constraints' => [new NotBlank(['message' => 'ne peut pas être vide'])]
            ])
            ->add('phone', TelType::class, [
                'attr' => array(
                    'placeholder' => 'Téléphone de l\'utilisateur'
                ),
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
