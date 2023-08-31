<?php

// src/Form/WorkersType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkersType extends AbstractType
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
            ->add('name', TextType::class, [ 
                'label' => "Nom",
                'attr' => [
                    'placeholder' => "",
                ],
            ])
            ->add('surname', TextType::class, [ 
                'label' => "Prénom",
                'attr' => [
                    'placeholder' => "",
                ],
            ])
            ->add('roles', CollectionType::class, [
                'entry_type' => ChoiceType::class,
                'entry_options' => [
                    'choices' => [
                        'Administration' => 'ROLE_ADMIN',
                        'Employé' => 'ROLE_WORKER',
                        'Ecole' => 'ROLE_SCHOOL'
                    ],
                ],
                'label' => "Rôle",
            ])
            ->add('password', PasswordType::class, [ 
                'label' => "Mot de passe",
                'attr' => [
                    'placeholder' => "",
                ],
            ])
            ->add('nbrColor', IntegerType::class, [ 
                'label' => "Nombre de couleurs",
                'attr' => [
                    'placeholder' => "",
                ],
            ])
            ->add('nbrNb', IntegerType::class, [ 
                'label' => "Nombre en noir et blanc",
                'attr' => [
                    'placeholder' => "",
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
