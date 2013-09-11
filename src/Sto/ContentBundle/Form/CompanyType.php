<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Doctrine\ORM\EntityRepository,
    Sto\ContentBundle\Form\CompanyPhoneType,
    Sto\ContentBundle\Form\CompanyWorkingTimeType,
    Sto\ContentBundle\Form\CompanyManagerType,
    Sto\ContentBundle\Form\CompanySpecializationType,
    Sto\ContentBundle\Form\CompanyGalleryType;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'Краткое наименование',
                'attr' => [
                    'class' => 'input-xxlarge'
                ]
            ])
            ->add('phones','collection', array(
                'label' => ' ',
                'type' => new CompanyPhoneType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ))
            ->add('city', 'entity', [
                'label' => 'Город',
                'class' => 'StoCoreBundle:Country',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('country')
                        ->where('country.parent is not null')
                    ;
                },
                'attr' => [
                    'class' => 'chzn-select input-large'
                ]
            ])
            ->add('address', null, [
                'label' => 'Адрес',
                'attr' => [
                    'class' => 'inputFormEnter inputleftContDate'
                ]
            ])
            ->add('workingTime','collection', array(
                'label' => ' ',
                'type' => new CompanyWorkingTimeType($options['em']),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
            ))
            ->add('hourPrice', null, [
                'label' => 'Стоимость нормочаса',
                'required' => false,
                'attr' => [
                    'class' => 'inputCost'
                ],
            ])
            ->add('currency', null, [
                'label' => 'Валюта',
                'empty_value' => 'Валюта',
                'attr' => [
                    'class' => 'styled1 withContainer'
                ]
            ])
            ->add('specializations', 'collection', [
                'label' => false,
                'type' => new CompanySpecializationType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ])
            ->add('logo', null, [
                'label' => 'Логотип компании',
                'required' => false,
                'attr' => [
                    'data-image' => 'logo',
                    'class' => 'hideLogoInput',
                ]
            ])
            ->add('slogan', 'text', [
                'label' => 'Девиз (слоган)',
                'required' => false,
                'attr' => [
                    'class' => 'input-xxlarge'
                ]
            ])
            ->add('fullName', 'text', [
                'label' => 'Полное наименование',
                'required' => true,
                'attr' => [
                    'class' => 'input-xxlarge'
                ]
            ])
            ->add('web', 'text', [
                'label' => 'Адрес сайта',
                'required' => false,
            ])
            ->add('additionalServices', 'entity', [
                'label' => 'Дополнительные услуги',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'class' => 'StoCoreBundle:Dictionary\AdditionalService',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.name')
                    ;
                },
                'attr' => [
                    'class' => 'someClass'
                ]
            ])
            ->add('skype', null, [
                'label' => 'Skype',
                'required' => false,
            ])
            ->add('email', 'email', [
                'label' => 'E-mail',
                'required' => false,
            ])
            ->add('createtDate', 'datetime', [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'Начало работы на рынке',
                'required' => false,
                'attr' => [
                    'class' => "inputData",
                    'data-format' => "yyyy-MM-dd"
                ]
            ])
            ->add('notes', 'textarea', [
                'label' => 'Дополнительное описание деятельности компании',
                'required' => false,
                'attr' => [
                    'rows' => '3',
                    'style' => 'width: 100%'
                ]
            ])
            ->add('gps', null, [
                'label' => 'Координаты на карте',
                'required' => true,
                'attr' => [
                    'style' => 'display:none'
                ]
            ])
            ->add('linkVK', 'text', [
                'label' => 'Группа Vkontakte',
                'required' => false,
                'attr' => [
                    'class' => 'inputFormEnter iputVkC'
                ]
            ])
            ->add('linkFB', 'text', [
                'label' => 'Страница Facebook',
                'required' => false,
                'attr' => [
                    'class' => 'inputFormEnter iputFaceC'
                ]
            ])
            ->add('linkTW', 'text', [
                'label' => 'Twitter',
                'required' => false,
                'attr' => [
                    'class' => 'inputFormEnter iputTwittC'
                ]
            ])
            ->add('companyManager','collection', array(
                'label' => ' ',
                'type' => new CompanyManagerType($options['em']),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ))
            ->add('gallery','collection', array(
                'label' => ' ',
                'type' => new CompanyGalleryType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => 'Sto\CoreBundle\Entity\Company'
            ])
            ->setRequired([
                'em',
            ])
            ->setAllowedTypes([
                'em' => 'Doctrine\Common\Persistence\ObjectManager',
            ])
        ;
    }

    public function getName()
    {
        return 'sto_content_company_registration';
    }
}
