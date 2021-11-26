<?php

namespace App\Form;

use App\Entity\Article;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

use Vich\UploaderBundle\Form\Type\VichImageType;

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
                    'constraints' => [new NotBlank(['message' => ': ne peut pas être vide'])]

                ]
            )
            ->add('content', TextareaType::class, [
                'attr' => array(
                    'placeholder' => 'Contenu de l\'article'
                ),
                'required' => true,
                'constraints' => [new NotBlank(['message' => ': ne peut pas être vide'])]

            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
            ])
            ->add('tags', null, [
                'expanded' => true,
                'multiple' => true,
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
