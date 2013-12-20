<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
use Doctrine\ORM\EntityRepository;
use Sto\ContentBundle\Form\UserContactEmailType;
use Sto\ContentBundle\Form\UserContactsType;

class UserProfileType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, [
                'label' => 'Имя',
                'attr' => [
                    'class' => 'span6 inputFormEnter'
                ]
            ])
            ->add('email', null, [
                'label' => 'E-mail',
                'attr' => [
                    'class' => 'span6 inputFormEnter'
                ]
            ])
            ->add('phoneNumber', null, [
                'label' => 'Телефон',
                'required' => false,
                'attr' => [
                    'class' => 'span6 inputFormEnter'
                ]
            ])
            ->add('avatar', null, [
                'label' => 'Аватара',
                'required' => false,

            ])
            ->add('birthDate', 'datetime', [
                'label' => 'Дата рождения',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'required' => false,
                'attr' => [
                    'class' => "inputData init-ui-datepicker-birthdate",
                    'data-format' => "dd-MM-yyyy"
                ]
            ])
            ->add('gender','choice', [
                'label' => 'Пол',
                'required' => false,
                'choices' => [
                    'male'=>'М',
                    'female'=>'Ж'
                ],
                'expanded' => true,
                'attr' => [
                    'class' => 'genderSelect'
                ]
            ])
            ->add('city', 'entity', [
                'label' => 'Город',
                'required' => false,
                'class' => 'StoCoreBundle:Country',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('country')
                        ->where('country.parent is not null')
                    ;
                },
                'attr' => [
                    'class' => 'chzn-select span6'
                ]
            ])
            ->add('linkVK', null, [
                'label' => 'ВКонтакте',
                'required' => false,
                'attr' => [
                    'style' => 'width:324px'
                ]
            ])
            ->add('linkFB', null, [
                'label' => 'Facebook',
                'required' => false,
                'attr' => [
                    'style' => 'width:270px'
                ]
            ])
            ->add('linkGP', null, [
                'label' => 'Google+',
                'required' => false,
                'attr' => [
                    'style' => 'width:294px'
                ]
            ])
            ->add('autoProfilesLinks', null, [
                'label' => 'Авто профиль',
                'required' => false,
                'attr' => [
                    'style' => 'width:263px'
                ]
            ])
            ->add('description', 'textarea', [
                'label' => 'О себе',
                'required' => false,
                'attr' => [
                    'rows' => 4,
                    'class '=> 'span6 inputFormEnter'
                ]
            ])
            ->add('contactEmails', 'collection', [
                'type' => new UserContactEmailType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'label' => false,
                'by_reference' => false,
            ])
            ->add('contacts', 'collection', [
                'type' => new UserContactsType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'label' => false,
                'by_reference' => false,
            ])
        ;
    }

    public function getName()
    {
        return 'sto_contentbundle_userprofile';
    }
}
