<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ->add('company', null, [
                'label' => 'Компания',
                'required' => true,
                'attr' => [
                    'class' => 'select2'
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
            ->add('services', null, [
                'label' => 'Services',
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
                'render_optional_text' => false
            ])
            ->add('image2', 'file', [
                'label' => 'Image2',
                'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                'property_path' => 'image2',
                'required' => false,
                'label_render' => false,
                'render_optional_text' => false
            ])
            ->add('image3', 'file', [
                'label' => 'Image3',
                'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                'property_path' => 'image3',
                'required' => false,
                'label_render' => false,
                'render_optional_text' => false
            ])
            ->add('terms', null, [
                'label' => 'Terms',
                'required' => false,
                'render_optional_text' => false,
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
                'label' => 'Place',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('contactInformation', 'textarea', [
                'label' => 'Контактная информация',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'rows' => 4,
                    'style '=> 'width:100%'
                ]
            ])
            ->add('type', null, [
                'label' => 'Types',
                'required' => true,
                'attr' => [
                    'class' => 'select2'
                ]
            ])
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
        return 'sto_admin_deal';
    }
}
