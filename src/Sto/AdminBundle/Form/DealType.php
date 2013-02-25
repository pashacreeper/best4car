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
                'label' => 'Deal name',
                'required' => true,
            ])
            ->add('company', null, [
                'label' => 'Company',
                'required' => true,
                'empty_value' => 'Choose company',
                'empty_data'  => null
            ])
            ->add('description', 'textarea', [
                'label' => 'Description',
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
            ->add('image', null, [
                'label' => 'Image',
                'required' => false,
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
                'label' => 'Start date',
                'required' => true,
            ])
            ->add('endDate', 'date', [
                'widget' => 'single_text',
                'datepicker' => true,
                'label' => 'End date',
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
                'label' => 'Contact Information',
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
