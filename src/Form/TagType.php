<?php

namespace App\Form;

use App\Entity\Tag;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom du tag',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner un nom pour le tag',
                    ]),
                ],
            ])
            ->add('colors',EntityType::class, array(
                'class' => 'App\Entity\Colors',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c');
                }, ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
