<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationStudentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('profilePicture', FileType::class, [
                'label' => 'Photo de profil',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new Image([
                    ])
                ],
            ])
            ->add('pseudo', TextType::class, [
                'label' => "Pseudo",
                'attr' => ['placeholder' => 'Pseudo'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisissez un pseudo.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre pseudo doit comporter au moins {{ limit }} caractères.',
                        'max' => 14,
                        'maxMessage' => 'Votre pseudo doit comporter au max {{ limit }} caractères.',
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => 'Email'],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'required' => true,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Mot de passe',
                'attr' => ['placeholder' => 'Mot de passe']],
                'second_options' => ['label' => 'Confirmer mot de passe',
                    'attr' => ['placeholder' => 'Confirmer mot de passe']],
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Choisissez un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'J\'accepte les conditions d\'utilisations.',
                'constraints' => [
                    new IsTrue([
                        'message' => 'You devez accepter les conditions d\'utilisation.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
