<?php

namespace App\Form;

use App\Entity\CatTypePaper;
use App\Entity\TypePaper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class PaperFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', TextType::class, [
                'label' => "Nom et épaisseur du papier",
            'attr' => [
            'placeholder' => "",
        ],

            ])
            ->add('Price', NumberType::class, [
                'label' => "Prix par feuille",
                'attr' => [
                'placeholder' => "",
            ],
            ])
            ->add('catTypePaper', EntityType::class, [
                'class' =>CatTypePaper::class,
                'choice_label' => 'catName', 

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TypePaper::class,
        ]);
    }
}
