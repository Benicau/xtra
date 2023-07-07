<?php

namespace App\Form;

use App\Entity\Bindings;
use App\Entity\CatBindings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class BindingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Name', TextType::class, [
            'label' => "Nom reliures",
        'attr' => [
        'placeholder' => "",
    ],

        ])
        ->add('Price', NumberType::class, [
            'label' => "Prix",
            'attr' => [
            'placeholder' => "",
        ],
        ])
            ->add('CatBindings', EntityType::class, [
                'class' =>CatBindings::class,
                'choice_label' => 'Name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bindings::class,
        ]);
    }
}
