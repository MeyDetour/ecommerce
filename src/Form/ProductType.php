<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;

use Doctrine\DBAL\Types\BooleanType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'Nom du produit',

            ])
            ->add('description',TextareaType::class,[
                'label'=>'Décrivez votre produit de maniere courte'
            ])
       ->add('longDescription',TextareaType::class,[
                'label'=>'Décrivez précisement votre produit'
            ])
            ->add('quantity',IntegerType::class,[
                'label'=>'Quantité',
                'data'=>0
            ])
            ->add('price',MoneyType::class,[
                'label'=>'Prix'
            ]) ->add('visibility',CheckboxType::class,[
                'data'=>true
            ])

            ->add('categories',EntityType::class,[
                'class'=>Category::class,
                'label'=>'Ajouter vos catégories',
                'query_builder' => function (CategoryRepository $categoryRepository): QueryBuilder {
                    return $categoryRepository->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
                'multiple'=>true,
                'expanded'=>true,
                'required'=>false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
