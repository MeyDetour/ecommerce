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
            ->add('country',ChoiceType::class,[
                    'label'=>'Selectionnez un pays',
                    'choices' => [
                        'Allemagne' => 'Allemagne',
                        'Argentine' => 'Argentine',
                        'Australie' => 'Australie',
                        'Belgique' => 'Belgique',
                        'Brésil' => 'Brésil',
                        'Canada' => 'Canada',
                        'Chili' => 'Chili',
                        'Chine' => 'Chine',
                        'Corée du Sud' => 'Corée du Sud',
                        'Danemark' => 'Danemark',
                        'Égypte' => 'Égypte',
                        'Espagne' => 'Espagne',
                        'États-Unis' => 'États-Unis',
                        'Finlande' => 'Finlande',
                        'France' => 'France',
                        'Grèce' => 'Grèce',
                        'Inde' => 'Inde',
                        'Irlande' => 'Irlande',
                        'Islande' => 'Islande',
                        'Italie' => 'Italie',
                        'Japon' => 'Japon',
                        'Luxembourg' => 'Luxembourg',
                        'Mexique' => 'Mexique',
                        'Norvège' => 'Norvège',
                        'Nouvelle-Zélande' => 'Nouvelle-Zélande',
                        'Pays-Bas' => 'Pays-Bas',
                        'Pologne' => 'Pologne',
                        'Portugal' => 'Portugal',
                        'Royaume-Uni' => 'Royaume-Uni',
                        'Russie' => 'Russie',
                        'Suède' => 'Suède',
                        'Suisse' => 'Suisse',
                        'Turquie' => 'Turquie',
                        'Autre ' => 'other',
                    ],]
            )
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
