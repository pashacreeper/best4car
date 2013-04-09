<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class UserProfileType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('firstName', null, [
                'label' => 'Имя',
            ])
            ->add('rating', null, [
                'label' => 'Рейтинг',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('phoneNumber', null, [
                'label' => 'Номер телефона',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('ratingGroupId', null, [
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('avatar', null, [
                'label' => 'Аватара',
                'required' => false,
                'render_optional_text' => false,
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
                'choices' => array('male'=>'Male', 'female'=>'Female'),
                'expanded' => true,
            ])
            ->add('cityId', null, [
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('linkVK', null, [
                'label' => 'Ссылка на профиль vk.com',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('linkFB', null, [
                'label' => 'Ссылка на профиль facebook.com',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('linkGP', null, [
                'label' => 'Ссылка на профиль plus.google.com',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('autoProfilesLinks', null, [
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('contentGroupId', null, [
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
