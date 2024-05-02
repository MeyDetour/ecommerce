<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email' , EmailType::class,[
                'label'=>'Votre e-mail'
            ])
            ->add('firstName',TextType::class,[
                'label'=>'Votre prénom'
            ])
            ->add('lastName',TextType::class,[
                'label'=>'Votre nom'
            ])
            ->add('genre',ChoiceType::class,[
                'label'=>'Votre genre',
                'choices'  => [
                    'Femme' => 'F',
                    'Homme' => 'H',
                    'Autre' => null,

                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'attr' => ['autocomplete' => 'new-password'],
                'invalid_message' => 'The password fields must match.',
                'first_options' => ['label' => 'Mot de passe','attr' => ['class' => 'inputPasssword input-password-field']],
                'second_options' => [ 'label' => 'Répétez votre mot de passe', 'attr' => ['class' => 'inputPassswordConfirm input-password-field']],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'votre mot de passe doit faire plus de {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
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
