<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('firstName', null, [
                'label' => 'Имя',
                'render_optional_text' => false,
                'required' => true,
                'attr' => [
                    'class' => 'inputFormEnter span6'
                ]
            ])
            ->add('username', 'text', [
                'label' => 'Ник',
                'required' => true,
                'attr' => [
                    'class' => 'inputFormEnter span6'
                ]
            ])
            ->add('email', 'email', [
                'label' => 'Ваш email',
                'required' => true,
                'attr' => [
                    'class' => 'inputFormEnter span6'
                ]
            ])
            ->add('plainPassword', 'repeated', [
                'type' => 'password',
                'options' => [
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => [
                        'class' => 'inputFormEnter'
                    ]
                ],
                'first_options' => [
                    'label' => 'form.password',
                    'attr' => [
                        'style' => 'width: 212px'
                    ]
                ],
                'second_options' => [
                    'label' => 'form.password_confirmation',
                    'attr' => [
                        'style' => 'width: 212px'
                    ]
                ],
                'invalid_message' => 'fos_user.password.mismatch',
            ])
            ->add('captcha', 'captcha', [
                'reload' => true,
                'bypass_code' => '1234567',
                'label' => "Введите текст изображенный на картинке",
                'attr' => [
                    'class' => 'inputFormEnter span2 clear'
                ]
            ])
            ->add('city', 'entity', [
                'label' => 'Город',
                'class' => 'StoCoreBundle:Country',
                'query_builder' => function($er) {
                    return $er->createQueryBuilder('country')
                        ->where('country.parent is not null')
                    ;
                },
                'attr' => [
                    'class' => 'select2'
                ]
            ])
        ;
    }

    public function getName()
    {
        return 'sto_contentbundle_registration';
    }
}
