<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',TextType::class)
            ->add('lastName',TextType::class)
            ->add('phone',TelType::class)
            ->add('country', ChoiceType::class, [
                'label' => 'Sélectionnez un pays',
                'choices' => [
                    'FR' => 'France',
                    'DE' => 'Allemagne',
                    'AR' => 'Argentine',
                    'AU' => 'Australie',
                    'BE' => 'Belgique',
                    'BR' => 'Brésil',
                    'CA' => 'Canada',
                    'CL' => 'Chili',
                    'CN' => 'Chine',
                    'KR' => 'Corée du Sud',
                    'DK' => 'Danemark',
                    'EG' => 'Égypte',
                    'ES' => 'Espagne',
                    'US' => 'États-Unis',
                    'FI' => 'Finlande',
                    'GR' => 'Grèce',
                    'IN' => 'Inde',
                    'IE' => 'Irlande',
                    'IS' => 'Islande',
                    'IT' => 'Italie',
                    'JP' => 'Japon',
                    'LU' => 'Luxembourg',
                    'MX' => 'Mexique',
                    'NO' => 'Norvège',
                    'NZ' => 'Nouvelle-Zélande',
                    'NL' => 'Pays-Bas',
                    'PL' => 'Pologne',
                    'PT' => 'Portugal',
                    'GB' => 'Royaume-Uni',
                    'RU' => 'Russie',
                    'SE' => 'Suède',
                    'CH' => 'Suisse',
                    'TR' => 'Turquie',
                    'Autre ' => 'other',
                ],
            ])
            ->add('codePostal',NumberType::class,
            [
                'label'=>'Entrez votre code postal'
            ])

            ->add('city',TextType::class)
            ->add('adresse',TextType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
