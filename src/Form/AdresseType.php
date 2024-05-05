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
                    'France' => 'FR',
                    'Allemagne' => 'DE',
                    'Argentine' => 'AR',
                    'Australie' => 'AU',
                    'Belgique' => 'BE',
                    'Brésil' => 'BR',
                    'Canada' => 'CA',
                    'Chili' => 'CL',
                    'Chine' => 'CN',
                    'Corée du Sud' => 'KR',
                    'Danemark' => 'DK',
                    'Égypte' => 'EG',
                    'Espagne' => 'ES',
                    'États-Unis' => 'US',
                    'Finlande' => 'FI',
                    'Grèce' => 'GR',
                    'Inde' => 'IN',
                    'Irlande' => 'IE',
                    'Islande' => 'IS',
                    'Italie' => 'IT',
                    'Japon' => 'JP',
                    'Luxembourg' => 'LU',
                    'Mexique' => 'MX',
                    'Norvège' => 'NO',
                    'Nouvelle-Zélande' => 'NZ',
                    'Pays-Bas' => 'NL',
                    'Pologne' => 'PL',
                    'Portugal' => 'PT',
                    'Royaume-Uni' => 'GB',
                    'Russie' => 'RU',
                    'Suède' => 'SE',
                    'Suisse' => 'CH',
                    'Turquie' => 'TR',
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
