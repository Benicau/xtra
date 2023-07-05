<?php

namespace App\Form;

use App\Entity\Pricecopycolor;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;



class PriceCopyColorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Begin', IntegerType::class, [
                'label' => "Début",
                'attr' => [
                'placeholder' => "",
            ],
            ])
            ->add('End', IntegerType::class, [
                'label' => "Fin",
                'attr' => [
                'placeholder' => "",
            ],
            ])
            ->add('Price', NumberType::class, [
                'label' => "Prix par page",
                'attr' => [
                'placeholder' => "",
            ],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pricecopycolor::class,
        ]);
    }
}
