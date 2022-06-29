<?php

namespace App\Form;

use App\Entity\Article;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                    'attr' => array(
                        'placeholder' => 'Titre de l\'article'
                    ),
                    'required' => true,
                    'constraints' => [new NotBlank(['message' => 'ne peut pas être vide'])]

                ]
            )
            ->add('content', TextareaType::class, [
                'attr' => array(
                    'placeholder' => 'Contenu de l\'article'
                ),
                'required' => true,
                'constraints' => [new NotBlank(['message' => 'ne peut pas être vide'])]

            ])
            ->add('images', FileType::class, [
                'required' => false,
                'multiple' => true,
                'label' => false,
                'mapped' => false,

            ])
            ->add('mainImage', FileType::class, [
                'required' => false,
                'multiple' => false,
                'label' => false,
                'mapped' => false,

            ])
            ->add('tag', null, [
                'expanded' => true,
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
