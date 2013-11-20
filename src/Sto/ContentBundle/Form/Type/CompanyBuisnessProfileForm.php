<?php
namespace Sto\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sto\CoreBundle\Validator\Constraints\ConstraintCompanySpecialization;
use Sto\ContentBundle\Form\CompanySpecializationType;

class CompanyBuisnessProfileForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('specializations', 'collection', [
                'label' => false,
                'type' => new CompanySpecializationType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'constraints' => [
                    new ConstraintCompanySpecialization(),
                ],
            ])
            ->add('additionalServices', 'entity', [
                'label' => 'Дополнительные услуги',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'class' => 'StoCoreBundle:Dictionary\AdditionalService',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.position', 'ASC')
                    ;
                },
                'attr' => [
                    'class' => 'someClass'
                ]
            ])
            ->add('autos', 'entity', [
                'label' => 'Специализация на марке',
                'multiple' => true,
                'class' => 'StoCoreBundle:Mark',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('mark')
                        ->where('mark.visible = true')
                    ;
                },
                'required' => false,
                'attr' => [
                    'class' => 'chosen-multiple span6 chzn-select',
                    'data-placeholder' => 'Выберете марки автомобилей'
                ]
            ])
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
            ->add('description', 'textarea', [
                'label' => 'Описание компании',
                'required' => false,
                'attr' => [
                    'class' => 'description-textarea',
                    'data-length' => '1250',
                    'rows' => '3',
                    'style' => 'width: 500px',
                ]
            ])
        ;
    }

    public function getName()
    {
        return 'sto_company_reguister_business_profile';
    }
}
