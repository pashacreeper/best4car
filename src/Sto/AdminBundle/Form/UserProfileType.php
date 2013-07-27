<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
use Doctrine\ORM\EntityRepository;

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
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'span6 inputFormEnter'
                ]
            ])
            ->add('avatar', null, [
                'label' => 'Аватара',
                'label_render' => false,
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('birthDate', 'datetime', [
                'label' => 'Дата рождения',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'required' => false,
                'attr' => [
                    'class' => "inputData",
                    'data-format' => "yyyy-MM-dd"
                ]
            ])
            ->add('gender','choice', [
                'label' => 'Пол',
                'required' => false,
                'render_optional_text' => false,
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
                'class' => 'StoCoreBundle:Dictionary\Country',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('country')
                        ->where('country.parent is not null')
                    ;
                },
                'attr' => [
                    'class' => 'span4'
                ]
            ])
            ->add('linkVK', null, [
                'label' => 'ВКонтакте',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'style' => 'width:324px'
                ]
            ])
            ->add('linkFB', null, [
                'label' => 'Facebook',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'style' => 'width:270px'
                ]
            ])
            ->add('linkGP', null, [
                'label' => 'Google+',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'style' => 'width:294px'
                ]
            ])
            ->add('autoProfilesLinks', null, [
                'label' => 'Авто профиль',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'span6 inputFormEnter'
                ]
            ])
            ->add('description', 'textarea', [
                'label' => 'О себе',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'rows' => 4,
                    'class '=> 'span6 inputFormEnter'
                ]
            ])
        ;
    }

    public function getName()
    {
        return 'sto_adminbundle_userprofiletype';
    }
}
