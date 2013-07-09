<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Sto\AdminBundle\Form\CompanyPhoneType;
use Sto\ContentBundle\Form\CompanyWorkingTimeType;
use Sto\ContentBundle\Form\CompanyManagerType;
use Sto\AdminBundle\Form\CompanyGalleryType;

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
                'label' => '',
                'type' => new CompanyPhoneType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'show_legend' => false,
                'widget_add_btn' =>[
                    'icon' => 'b4c-plus',
                    'label' => 'add phone',
                    'attr' => [
                         'class' => 'btn btn-primary btn-small'
                    ]
                ],
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'widget_remove_btn' => [
                        'label' => '-',
                        'attr' => [
                            'class' => 'btn btn-danger btn-small '
                        ]
                    ],
                    'widget_control_group' => false,
                )
            ))
            ->add('city', 'entity', [
                'label' => 'Город',
                'class' => 'StoCoreBundle:Dictionary\Country',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('country')
                        ->where('country.parent is not null')
                    ;
                },
                'attr' => [
                    'class' => 'select2 input-large'
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
            ))
            ->add('hourPrice', null, [
                'label' => 'Стоимость нормочаса',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'inputCost'
                ],
            ])
            ->add('currency', null, [
                'label' => 'Валюта',
                'empty_value' => 'Валюта',
                'attr' => [
                    'class' => 'styled1'
                ]
            ])
            ->add('services', 'entity', [
                'label' => 'Услуги',
                'multiple' => true,
                'expanded' => true,
                'class' => 'StoCoreBundle:Dictionary\Company',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->where('ct.parent is not null')
                    ;
                },
                'attr' => [
                    'class' => 'select2',
                    'style' => 'width:100%;'
                ]
            ])
            ->add('specialization', 'entity', [
                'label' => 'Основная специализация',
                'multiple' => true,
                'expanded' => true,
                'class' => 'StoCoreBundle:Dictionary\Company',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->where('ct.parent is null')
                    ;
                },
                'attr' => [
                    'class' => 'select2',
                    'style' => 'width:100%;'
                ]
            ])
            ->add('logo', null, [
                'label' => 'Логотип компании',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'data-image' => 'logo',
                    'class' => 'hideLogoInput'
                ]
            ])
            ->add('slogan', 'text', [
                'label' => 'Девиз (слоган)',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'input-xxlarge'
                ]
            ])
            ->add('fullName', 'text', [
                'label' => 'Полное наименование',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'input-xxlarge'
                ]
            ])
            ->add('web', 'text', [
                'label' => 'Адрес сайта',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('additionalServices', 'entity', [
                'label' => 'Дополнительные услуги',
                'required' => false,
                'render_optional_text' => false,
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
                'render_optional_text' => false
            ])
            ->add('email', 'email', [
                'label' => 'E-mail',
                'required' => false,
                'render_optional_text' => false
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
                'render_optional_text' => false,
                'attr' => [
                    'rows' => '3',
                    'style' => 'width: 100%'
                ]
            ])
            ->add('gps', 'hidden', [
                'label' => 'Координаты Yandex-карты',
                'required' => true,
            ])
            ->add('linkVK', 'text', [
                'label' => 'Группа Vkontakte',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'inputFormEnter iputVkC'
                ]
            ])
            ->add('linkFB', 'text', [
                'label' => 'Страница Facebook',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'inputFormEnter iputFaceC'
                ]
            ])
            ->add('linkTW', 'text', [
                'label' => 'Twitter',
                'required' => false,
                'render_optional_text' => false,
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
                'show_legend' => false
            ))
            ->add('contacts','collection', array(
                'label' => ' ',
                'type' => new CompanyContactsType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'show_legend' => false,
                'widget_add_btn' =>[
                    'icon' => 'b4c-plus',
                    'label' => 'add contact',
                    'attr' => [
                         'class' => 'btn btn-primary btn-small'
                    ]
                ],
                'options' => array( // options for collection fields
                    'label_render' => false,
                    'widget_remove_btn' => [
                        'label' => '-',
                        'attr' => [
                            'class' => 'btn btn-danger btn-small '
                        ]
                    ],
                    'widget_control_group' => false,
                )
            ))
            ->add('gallery','collection', array(
                'label' => ' ',
                'type' => new CompanyGalleryType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'show_legend' => false,
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
