<?php
namespace Sto\ContentBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Sto\ContentBundle\Form\CompanySpecializationType;
use Sto\CoreBundle\Validator\Constraints\ConstraintCompanySpecialization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyBusinessProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('specializations', 'collection', [
                'label' => false,
                'type' => new CompanySpecializationType(),
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => false,
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
                    'class' => 'span6 chzn-select-autos',
                    'data-placeholder' => 'Выберете марки автомобилей'
                ]
            ])
            ->add('allAuto', 'checkbox', [
                'required' => false,
            ])
            ->add('hourPrice', null, [
                'label' => 'Стоимость нормочаса',
                'required' => false,
                'attr' => [
                    'class' => 'inputCost'
                ],
                'constraints' => [
                    new Assert\Type([
                        'type' => 'integer',
                        'message' => 'Необходимо ввести число'
                    ]),
                    new Assert\Range([
                        'min' => 0,
                        'max' => 100000
                    ])
                ]
            ])
            ->add('currency', null, [
                'label' => 'Валюта',
                'empty_value' => false,
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
                ],
                'constraints' => [
                    new Assert\Length([
                        'max' => 1250
                    ])
                ]
            ])
        ;
    }

    public function getName()
    {
        return 'sto_company_register_business_profile';
    }
}
