<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ClientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => "Email de l'utilisateur",
            'attr' => [
                'placeholder' => "",
            ],
        ])
            ->add('name', TextType::class, [ // Champ pour le nom
                'label' => "Nom",
                'attr' => [
                    'placeholder' => "",
                ],
            ])
            ->add('surname', TextType::class, [ // Champ pour le prénom
                'label' => "Prénom",
                'attr' => [
                    'placeholder' => "",
                ],
            ])
            ->add('nbrColor', IntegerType::class, [ // Champ pour le nombre de couleurs
                'label' => "Couleurs",
                'attr' => [
                    'placeholder' => "",
                ],
            ])
            ->add('nbrNb', IntegerType::class, [ // Champ pour le nombre en noir et blanc
                'label' => "Noir et blanc",
                'attr' => [
                    'placeholder' => "",
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
