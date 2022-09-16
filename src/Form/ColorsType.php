<?php

namespace App\Form;

use App\Entity\Colors;
use App\Entity\Tag;
use App\Repository\ColorsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ColorsType extends AbstractType
{
    private $colorsRepository;

    public function __construct(ColorsRepository $colorsRepository)
    {
        $this->colorsRepository = $colorsRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('color', EntityType::class, [
                'class' => Colors::class,
                'choices' => $this->colorsRepository->findAll(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}