<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Sto\ContentBundle\Form\DataTransformer\TimestampToDateTransformer;

class DealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new TimestampToDateTransformer();
        $builder
            ->add('name', 'text', [
                'label' => 'Название акции',
                'required' => true,
                'attr' => [
                    "class" => "inputField span12",
                ]
            ])
            ->add('description', 'textarea', [
                'label' => 'Описание',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    "rows" => 4,
                    "class" => "span12",
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
                    'class' => 'chosen-multiple'
                ]
            ])
            ->add('auto', null, [
                'label' => 'Автомабили',
                'required' => false,
                'render_optional_text' => false,
                'multiple' => true,
                'class' => 'StoCoreBundle:Catalog\Base',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('catalog')
                        ->where('catalog.parent is null')
                    ;
                },
                'attr' => [
                    'class' => 'chosen-multiple'
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
                    "rows" => 4,
                    "class" => "span12",
                ]
            ])
            ->add(
                $builder->create('startDate', 'text', [
                    'label' => 'Начало',
                    'required' => true,
                    'attr' => [
                        'class' => 'inputTime',
                        'data-format' => 'hh:mm:ss'
                    ]
                ])->addModelTransformer($transformer)
            )
            ->add(
                $builder->create('endDate', 'text', [
                    'label' => 'Начало',
                    'required' => true,
                    'attr' => [
                        'class' => 'inputTime',
                        'data-format' => 'hh:mm:ss'
                    ]
                ])->addModelTransformer($transformer)
            )
            ->add('startTime', 'text', [
                'label' => 'Start time',
                'required' => true,
                'attr' => [
                    'class' => 'inputTime',
                    'data-format' => "hh:mm:ss"
                ]
            ])
            ->add('endTime', 'text', [
                'label' => 'End time',
                'required' => true,
                'attr' => [
                    'class' => 'inputTime',
                    'data-format' => "hh:mm:ss"
                ]
            ])
            ->add('place', null, [
                'label' => 'Место проведе',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => "input-xxlarge inputField span12",
                    'style' => "display: block",
                    'onclick'=>"$('#myModal').modal();"
                ]
            ])
            ->add('contactInformation', 'text', [
                'label' => 'Контактное лицо',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    "class" => "inputField span12",
                ]
            ])
            ->add('type', null, [
                'label' => 'Тип акции',
                'required' => true,
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'styled'
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
