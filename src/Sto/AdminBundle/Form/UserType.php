<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', [
                'label' => 'Имя пользователя',
                'required' => true,
            ])
            ->add('email', 'email', [
                'label' => 'Электронная почта',
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
            ->add('groups', null, [
                'label' => 'Группа',
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('firstName', null, [
                'label' => 'Имя',
                'render_optional_text' => false
            ])
            ->add('lastName', null, [
                'label' => 'Фамилия',
                'render_optional_text' => false
            ])
            ->add('rating', null, [
                'label' => 'Рейтинг',
                'render_optional_text' => false
            ])
            ->add('ratingGroup', null, [
                'label' => 'Рейтинговая группа',
                'render_optional_text' => false
            ])
            ->add('phoneNumber', null, [
                'label' => 'Номер телефона',
                'render_optional_text' => false,
                'attr' => [
                    'data-mask' => '+7 (999) 999-99-99'
                ]
            ])
            ->add('avatar', null, [
                'label' => 'Аватар',
                'render_optional_text' => false
            ])
            ->add('birthDate', null, [
                'label' => 'Дата рождения',
                'widget' => 'single_text',
                'datepicker' => true,
                'render_optional_text' => false
            ])
            ->add('gender', 'choice', [
                'label' => 'Пол',
                'choices' => ['Мужской', 'Женский'],
                'render_optional_text' => false
            ])
            ->add('city', null, [
                'label' => 'Город',
                'render_optional_text' => false
            ])
            ->add('linkVK', null, [
                'label' => 'ВКонтакте',
                'render_optional_text' => false
            ])
            ->add('linkFB', null, [
                'label' => 'Facebook',
                'render_optional_text' => false
            ])
            ->add('linkGP', null, [
                'label' => 'Google+',
                'render_optional_text' => false
            ])
            ->add('autoProfilesLinks', null, [
                'label' => 'Авто профиль',
                'render_optional_text' => false
            ])
            ->add('linkGarage', null, [
                'label' => 'Гараж',
                'render_optional_text' => false
            ])
            ->add('description', 'textarea', [
                'label' => 'О себе',
                'render_optional_text' => false,
                'attr' => [
                    'rows' => 3,
                    'style' => 'width:100%'
                ]
            ])
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
        return 'sto_admin_user';
    }
}
