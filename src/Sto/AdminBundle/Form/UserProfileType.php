<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class UserProfileType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder
        ->add('firstName', null, [
                'label' => 'Имя',
            ])
        ->add('lastName', null, [
                'label' => 'Фамилия',
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
        ->add('avatarUrl', null, [
                'label' => 'URL Аватара',
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
                'required' => false,
                'render_optional_text' => false,
            ])
        ->add('linkFB', null, [
                'required' => false,
                'render_optional_text' => false,
            ])
        ->add('linkGP', null, [
                'required' => false,
                'render_optional_text' => false,
            ])
        ->add('autoProfilesLinks', null, [
                'required' => false,
                'render_optional_text' => false,
            ])
        ->add('linkGarage', null, [
                'required' => false,
                'render_optional_text' => false,
            ])
        ->add('contentGroupId', null, [
                'required' => false,
                'render_optional_text' => false,
            ])
        ->add('description', 'textarea', [
                'label' => 'Description',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'rows' => 4,
                    'class '=> 'span9'
                ]
            ])
        ->add('job', null, [
                'label' => 'Род занятий',
                'required' => false,
                'render_optional_text' => false,
                'class' => 'StoCoreBundle:Dictionary',
                'query_builder' => function (\Sto\CoreBundle\Repository\DictionaryRepository $repository) {
                         return $repository->createQueryBuilder('s')
                                ->where('s.parentId = ?1')
                                ->setParameter(1, 6);
                     }
            ])
        ;

    }

    public function getName()
    {
        return 'sto_adminbundle_userprofiletype';
    }
}
