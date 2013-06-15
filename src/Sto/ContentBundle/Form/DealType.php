<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class DealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', 'text', [
            'label' => 'Название акции',
            'required' => true,
            'attr' => [
            'style' => 'width:100%',
            ]
            ])
        ->add('description', 'textarea', [
            'label' => 'Описание',
            'required' => false,
            'render_optional_text' => false,
            'attr' => [
            'rows' => 4,
            'style '=> 'width:100%'
            ]
            ])
        ->add('autoServices', null, [
                'label' => 'Узлы и работы',
                'required' => false,
                'render_optional_text' => false,
                'multiple' => true,
                'class' => 'StoCoreBundle:Dictionary\autoServices',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->where('ct.parent is not null')
                        ->andWhere('ct.code is null')
                        ->groupBy('ct.parent')
                    ;
                },
                'attr' => [
                    'class' => 'select2'
                ]
            ])
        ->add('auto', null, [
            'label' => 'Автомабили',
            'required' => false,
            'render_optional_text' => false,
            'attr' => [
            'class' => 'select2'
            ]
            ])
        ->add('image', 'file', [
            'label' => 'Image',
            'data_class' => 'Symfony\Component\HttpFoundation\File\File',
            'property_path' => 'image',
            'required' => false,
            'label_render' => false,
            'render_optional_text' => false,
            'attr' => [
            'data-image' => 'image1'
            ]
            ])
        ->add('image2', 'file', [
            'label' => 'Image2',
            'data_class' => 'Symfony\Component\HttpFoundation\File\File',
            'property_path' => 'image2',
            'required' => false,
            'label_render' => false,
            'render_optional_text' => false,
            'attr' => [
            'data-image' => 'image2'
            ]
            ])
        ->add('image3', 'file', [
            'label' => 'Image3',
            'data_class' => 'Symfony\Component\HttpFoundation\File\File',
            'property_path' => 'image3',
            'required' => false,
            'label_render' => false,
            'render_optional_text' => false,
            'attr' => [
            'data-image' => 'image3'
            ]
            ])
        ->add('terms', 'textarea', [
            'label' => 'Условия участия',
            'required' => false,
            'render_optional_text' => false,
            'attr' => [
            'rows' => 4,
            'style '=> 'width:100%'
            ]
            ])
        ->add('startDate', 'date', [
            'widget' => 'single_text',
            'datepicker' => true,
            'label' => 'Начало',
            'required' => true,
            'attr' => [
            'class' => 'input-small',
            'style' => 'display: inline;'
            ]
            ])
        ->add('endDate', 'date', [
            'widget' => 'single_text',
            'datepicker' => true,
            'label' => 'Конец',
            'required' => true,
            'attr' => [
            'class' => 'input-small',
            'style' => 'display: inline;'
            ]
            ])
        ->add('startTime', 'time', [
            'label' => 'Start time',
            'required' => true,
            'attr' => [
            'style' => 'display: inline;'
            ]
            ])
        ->add('endTime', 'time', [
            'label' => 'End time',
            'required' => true,
            'attr' => [
            'style' => 'display: inline;'
            ]
            ])
        ->add('place', null, [
            'label' => 'Место проведе',
            'required' => false,
            'render_optional_text' => false,
            'attr' => [
            'class'=> 'input-xxlarge',
            'onclick'=>"$('#myModal').modal();"
            ]
            ])
        ->add('contactInformation', 'textarea', [
            'label' => 'Контактное лицо',
            'required' => false,
            'render_optional_text' => false,
            'attr' => [
            'rows' => 2,
            'style '=> 'width:100%'
            ]

            ])
        ->add('type', null, [
            'label' => 'Тип акции',
            'required' => false,
            'render_optional_text' => false,
            'attr' => [
            'class' => 'select2'
            ]
            ])
        ->add('gps', 'hidden', [])
        ->add('companyId', 'hidden', [])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\Deal'
            ]);
    }

    public function getName()
    {
        return 'sto_content_deal';
    }
}
