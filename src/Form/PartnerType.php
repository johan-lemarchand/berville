<?php

namespace App\Form;

use App\Entity\Partner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PartnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => array(
                    'placeholder' => 'Nom du partenaire'
                ),
                'required' => true,
                'constraints' => [new NotBlank(['message' => 'ne peut pas être vide'])]
            ])
            ->add('phone', TelType::class, [
                'attr' => array(
                    'placeholder' => 'Téléphone du partenaire'
                ),
                'required' => false,
            ])
            ->add('address', TextType::class, [
                'attr' => array(
                    'placeholder' => 'Adresse du partenaire'
                ),
                'required' => false,
            ])
            ->add('logo', FileType::class,[
                'required' => false,
                'label' => false,
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partner::class,
        ]);
    }
}
