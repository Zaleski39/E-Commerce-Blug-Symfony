<?php

// Formulaire pour la commande

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Permet de récupérer l'utilisateur avec la clef user.
        $user = $options['user'];

        // Construction du formulaire
        $builder
            ->add('address', EntityType:: class, [
                'class' =>Address::class,
                'required' => true,
                'choices' => $user->getAddresses(),
                'multiple' => false,
                'expanded' => true,
                // Ajouter une classe
                'attr' => [
                    'class' => 'blabla'
                ]
            ])
            ->add('carrier', EntityType:: class, [
                'class' =>Carrier::class,
                'required' => true,
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('informations', TextareaType::class, [
                'required'=> false,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user'=> array(),
        ]);
    }
}
