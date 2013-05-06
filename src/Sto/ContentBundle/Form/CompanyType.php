<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Sto\AdminBundle\Form\CompanyPhoneType,
    Sto\AdminBundle\Form\CompanyWorkingTimeType;;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'Сокращенное наименование'
            ])

            ->add('phones','collection', array(
                'label' => 'Телефон',
                'type' => new CompanyPhoneType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'show_legend' => false,
                'widget_add_btn' =>[
                    'icon' => 'plus-sign',
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
            ->add('address', null, [
                'label' => 'Адрес',
            ])
            ->add('workingTime','collection', array(
                'label' => 'Рабочее время',
                'type' => new CompanyWorkingTimeType($options['em']),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'show_legend' => false,
                'widget_add_btn' =>[
                    'icon' => 'plus-sign',
                    'label' => ' ',
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
            ->add('hourPrice', null, [
                'label' => 'Hour price',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'input-small'
                ],
            ])
            ->add('currency', null, [
            ])
            ->add('services', 'entity', [
                'label' => 'Services',
                'multiple' => true,
                'class' => 'StoCoreBundle:Dictionary\Company',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->where('ct.parent is not null')
                    ;
                },
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('specialization', 'entity', [
                'label' => 'Specialization',
                'multiple' => true,
                'class' => 'StoCoreBundle:Dictionary\Company',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->where('ct.parent is null')
                    ;
                },
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('logo', null, [
                'label' => 'Logo',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'data-image' => 'logo',
                ]
            ])
            ->add('slogan', 'textarea', [
                'label' => 'Девиз',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'rows' => '4',
                    'style' => 'width: 100%'
                ]
            ])
            ->add('fullName', 'text', [
                'label' => 'Полное наименование',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'style' => 'width: 100%',
                    'class' => 'input-xxlarge'
                ]
            ])
            ->add('web', 'url', [
                'label' => 'Адрес сайта',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('additionalServices', 'entity', [
                'label' => 'Additional services',
                'required' => false,
                'render_optional_text' => false,
                'multiple' => true,
                'class' => 'StoCoreBundle:Dictionary\AdditionalService',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.name')
                    ;
                },
                'attr' => [
                    'class' => 'select2'
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
            ->add('createtDate', 'date', [
                'widget' => 'single_text',
                'datepicker' => true,
                'label' => 'Createt date',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('notes', 'textarea', [
                'label' => 'Notes',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'rows' => '3',
                    'style' => 'width: 100%'
                ]
            ])
            ->add('gps', 'text', [
                'label' => 'Координаты Yandex-карты',
                'required' => true,
                'attr' => [
                    'data-mask' => '99.99,99.99',
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\Company'
        ]);

        $resolver->setRequired(array(
            'em',
        ));

        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

    public function getName()
    {
        return 'sto_content_company';
    }
}
