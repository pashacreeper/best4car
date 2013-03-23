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
            ])
            ->add('company', null, [
                'label' => 'Компания',
                'required' => true,
                'empty_value' => 'Выберите компанию',
                'empty_data'  => null
            ])
            ->add('description', 'textarea', [
                'label' => 'Описание',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'rows' => 4,
                    'class '=> 'span10'
                ]
            ])
            ->add('services', null, [
                'label' => 'Services',
                'required' => false,
                'render_optional_text' => false,
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
            ])
            ->add('endDate', 'date', [
                'widget' => 'single_text',
                'datepicker' => true,
                'label' => 'Конец',
                'required' => true,
            ])
            ->add('startTime', 'time', [
                'label' => 'Start time',
                'required' => true,
            ])
            ->add('endTime', 'time', [
                'label' => 'End time',
                'required' => true,
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
                    'class '=> 'span10'
                ]
            ])
            ->add('type', 'choice', [
                'label' => 'Types',
                'required' => true,
                'choices' => $this->getTypes(),
                'empty_value' => 'Choose types',
                'empty_data'  => null
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

    public function getTypes()
    {
        return [
            'Скидка',
            'Маркетинговое мероприятие',
            'Тест-драйв',
            'Презентация, день открытых дверей.',
            'Распродажа',
            'Сезонное предложение'
        ];
    }
}
