<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationCompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, [
                'label' => 'Имя',
                'render_optional_text' => false,
                'required' => true,
            ])
            ->add('username', 'text', [
                'label' => 'Ник',
                'required' => true,
            ])
            ->add('email', 'email', [
                'label' => 'Ваш email',
                'required' => true,
            ])
            ->add('plainPassword', 'repeated', [
                'first_name' => 'password',
                'second_name' => 'confirm_password',
                'invalid_message' => "Пароли не совпадают",
                'options' => [
                    'attr' => [
                        'placeholder' => '**********',
                        'render_optional_text' => false,
                    ],
                ],
                'required' => true,
                'type' => 'password',
            ])
            ->add('city', 'entity', [
                'label' => 'Город',
                'class' => 'StoCoreBundle:Dictionary\Country',
                'query_builder' => function($er) {
                    return $er->createQueryBuilder('country')
                        ->where('country.parent is not null')
                    ;
                },
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('captcha', 'captcha',[
                'reload' => true
            ]);
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\UserBundle\Entity\User'
        ]);
    }

    public function getName()
    {
        return 'sto_company_registration';
    }
}
