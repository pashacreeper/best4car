<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CompanyType extends AbstractType
{
    private $mode;

    public function __construct($mode = 'new')
    {
        $this->mode = $mode;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'Сокращенное наименование'
            ])
            ->add('phones', null, [
                'label' => 'Телефон',
                'attr' => [
                    'data-mask' => '+7 (999) 999-99-99'
                ]
            ])
            ->add('address', null, [
                'label' => 'Адрес',
            ])
            ->add('workingTime', null, [
                'label' => 'Время работы',
            ])
            ->add('hourPrice', null, [
                'label' => 'Hour price',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('rating', null, [
                'label' => 'Рейтинг',
                'required' => false,
                'render_optional_text' => false
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
                'render_optional_text' => false
            ])
            ->add('visible', null, [
                'label' => 'Visible',
                'required' => false,
                'render_optional_text' => false
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
                    'style' => 'width: 100%'
                ]
            ])
        ;
        if ($this->mode === 'edit') {
            $builder
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
                ->add('gps', null, [
                    'label' => 'Координаты GPS',
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
                ->add('socialNetworks', null, [
                    'label' => 'Social networks',
                    'required' => false,
                    'render_optional_text' => false
                ])
                ->add('reviews', 'entity', [
                    'label' => 'Reviews',
                    'required' => false,
                    'render_optional_text' => false,
                    'class' => 'StoUserBundle:User',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                        ;
                    },
                ])
                ->add('description', 'textarea', [
                    'label' => 'Краткое описание',
                    'required' => false,
                    'render_optional_text' => false,
                    'attr' => [
                        'rows' => '3',
                        'style' => 'width: 100%'
                    ]
                ])
                ->add('subscribable', null, [
                    'label' => 'Subscribable',
                    'required' => false,
                    'render_optional_text' => false
                ])
                ->add('managers', 'entity', [
                    'label' => 'Перечень менеджеров',
                    'required' => false,
                    'render_optional_text' => false,
                    'class' => 'StoUserBundle:User',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                       ;
                    },
                    'attr' => [
                        'class' => 'select2'
                    ]
                ])
                ->add('administratorContactInfo', 'textarea', [
                    'label' => 'Administrator contact',
                    'required' => false,
                    'render_optional_text' => false,
                    'attr' => [
                        'rows' => '3',
                        'style' => 'width: 100%'
                    ]
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
            ;
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\Company'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_company';
    }
}
