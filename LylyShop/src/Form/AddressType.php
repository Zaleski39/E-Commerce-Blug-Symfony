<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class, ['label' => 'Nom'])
            // ->add('campany', TextType::class, ['label' => 'Entreprise'])
            ->add('address', TextType::class, ['label' => 'Adresse'])
            ->add('complement', TextType::class, [
                'label' => 'Complément d\'adresse' ,
                'required' => false,])
            ->add('phone', TextType::class, ['label' => 'Téléphone'])
            ->add('postal', TextType::class, ['label' => 'Code postal'])
            ->add('city', TextType::class, ['label' => 'Ville'])
            ->add('country', CountryType::class, ['label' => 'Pays'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
