<?php

namespace App\Form;

use App\Entity\Abonnements;
use App\Entity\CatAbonnement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AbonnementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbcopy', IntegerType::class,[
                'label' => "Nombre de copie",
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
         
            ->add('catAbonnement', EntityType::class, [
                'class' =>CatAbonnement::class,
                'choice_label' => 'catName', // Remplacez 'propertyName' par la propriété appropriée de CatTypePaper à afficher

            ])




        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnements::class,
        ]);
    }
}
