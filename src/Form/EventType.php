<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                    'attr' => array(
                        'placeholder' => 'Titre de l\'évènement'
                    ),
                    'required' => true,
                    'constraints' => [new NotBlank(['message' => 'ne peut pas être vide'])]
                ]
            )
            ->add('date', DateType::class, [
                'attr' => array(
                    'placeholder' => 'Date de l\'évènement'
                ),
                'required' => false,
            ])
            ->add('place', TextType::class, [
                'attr' => array(
                    'placeholder' => 'Adresse de l\'évènement'
                ),
                'required' => false,
            ])
            ->add('city', TextType::class, [
                'attr' => array(
                    'placeholder' => 'Ville de l\'évènement'
                ),
                'required' => true,
            ])
            ->add('zip', NumberType::class, [
                'attr' => array(
                    'placeholder' => 'Code postal de l\'évènement'
                ),
                'required' => true,
            ])
            ->add('content', TextareaType::class, [
                'attr' => array(
                    'placeholder' => 'Contenu de l\'évènement'
                ),
                'required' => true,
                'constraints' => [new NotBlank(['message' => 'ne peut pas être vide'])]
            ])
            /*->add('picture', VichImageType::class, [
                'required' => false,
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
