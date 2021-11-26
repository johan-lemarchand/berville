<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => array(
                    'placeholder' => 'Email de l\'utilisateur'
                ),
                'required' => true,
                'constraints' => [new NotBlank(['message' => 'ne peut pas être vide']), new Email(['message' => 'Votre email n\'est pas valide'])]
            ])
            ->add('role', null, [
                'expanded' => true,
            ])
            ->add('firstname', TextType::class, [
                'attr' => array(
                    'placeholder' => 'Nom de l\'utilisateur'
                ),
                'required' => true,
                'constraints' => [new NotBlank(['message' => 'ne peut pas être vide'])]
            ])
            ->add('birthday', DateType::class, [
                'attr' => array(
                    'placeholder' => 'Date de naissance de l\'utilisateur'
                ),
                'required' => false,
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
            ])
            ->add('address', TextType::class, [
                'attr' => array(
                    'placeholder' => 'Adresse de l\'utilisateur'
                ),
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
