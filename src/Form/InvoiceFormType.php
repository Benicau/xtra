<?php

namespace App\Form;

use App\Entity\Invoices;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', HiddenType::class )
            ->add('texte', HiddenType::class)
            ->add('paymentMethod', ChoiceType::class, [ 
                    'choices' => [
                        'Cash' => 'Cash', 
                        'Bancontact' => 'Bancontact'
                        
                    ],  
            ])
            ->add('total',HiddenType::class )
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoices::class,
        ]);
    }
}
