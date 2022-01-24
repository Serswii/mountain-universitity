<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', EmailType::class, [
                'label' => 'Пожалуйста введите email',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'autofocus' => 'autofocus',
                    'placeholder' => 'Здесь должен быть ваш email'
                ],
                'label_attr' => [
                    'class' => 'pb-2'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Пожалуйста заполните поле'
                    ])
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Я согласен на условия <a href="#">privacy policy</a>',
                'required' => true,
                'label_html' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'custom-control-input'
                ],
                'label_attr' => [
                    'class' => 'custom-control-label'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Пожалуйста поставьте галочку',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Пароль',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'pb-2'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Пожалуйста заполните поле',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Ваш пароль должен быть не менее {{ limit }} символов',
                        'max' => 4096,
                    ]),
                ],
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
