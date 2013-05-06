<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;
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
                    'class' => 'input-xlarge'
                ]
            ])
            ->add('lastName', null, [
                'label' => 'Фамилия',
                'render_optional_text' => false,
                'required' => true,
                'attr' => [
                    'class' => 'input-xlarge'
                ]
            ])
            ->add('username', 'text', [
                'label' => 'Ник',
                'required' => true,
                'attr' => [
                    'class' => 'input-xlarge'
                ]
            ])
            ->add('email', 'email', [
                'label' => 'Ваш email',
                'required' => true,
                'attr' => [
                    'class' => 'input-xlarge'
                ]
            ])
            ->add('plainPassword', 'repeated', [
                'type' => 'password',
                'options' => [
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => [
                        'class' => 'input-xlarge'
                    ]
                ],
                'first_options' => ['label' => 'form.password'],
                'second_options' => ['label' => 'form.password_confirmation'],
                'invalid_message' => 'fos_user.password.mismatch',
            ])
            ->add('captcha', 'captcha',[
                'reload' => true,
                'bypass_code' => '1234567',
                'attr' => [
                    'class' => 'input-xlarge'
                ]
            ])
            /*->add('ratingGroupId', 'hidden', [
                'attr' => [
                    'value' => '1'
                ]
            ])*/
            ;
        ;
    }

    /*public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\UserBundle\Entity\User'
        ]);
    }*/

    public function getName()
    {
        return 'sto_contentbundle_registration';
    }
}
