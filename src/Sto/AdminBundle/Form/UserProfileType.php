<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class UserProfileType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, [
                'label' => 'Имя',
            ])
            ->add('email', null, [
                'label' => 'E-mail',
            ])
            ->add('rating', null, [
                'label' => 'Рейтинг',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'input-small'
                ]
            ])
            ->add('phoneNumber', null, [
                'label' => 'Телефон',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('ratingGroup', null, [
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('avatar', null, [
                'label' => 'Аватара',
                'label_render' => false,
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'style' => 'display:none;'
                ]
            ])
            ->add('birthDate', 'date', [
                'label' => 'Дата рождения',
                'required' => false,
                'render_optional_text' => false,
                'widget' => 'single_text',
                'datepicker' => true
            ])
            ->add('gender','choice', [
                'label' => 'Пол',
                'required' => false,
                'render_optional_text' => false,
                'choices' => [
                    'male'=>'Male',
                    'female'=>'Female'
                ],
                'expanded' => true,
            ])
            ->add('city', null, [
                'label' => 'Город',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('linkVK', null, [
                'label' => 'ВКонтакте',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('vkontakteId', null, [
                'label' => 'Id пользователя Вконтакте',
                'render_optional_text' => false,
                'required' => false,
            ])
            ->add('linkFB', null, [
                'label' => 'Facebook',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('linkGP', null, [
                'label' => 'Google+',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('autoProfilesLinks', null, [
                'label' => 'Авто профиль',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('description', 'textarea', [
                'label' => 'О себе',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'rows' => 4,
                    'class '=> 'span9'
                ]
            ])
        ;
    }

    public function getName()
    {
        return 'sto_adminbundle_userprofiletype';
    }
}
