<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CompanySpecializationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', null, [
                'label' => false,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('company_type')
                        ->where('company_type.parent is null');
                },
                'attr' => [
                    'class' => 'inputFormEnter'
                ],
            ])
            ->add('subType', 'shtumi_dependent_filtered_entity', [
                'label' => false,
                'entity_alias' => 'subType_by_type',
                'parent_field' => 'type',
                'attr' => [
                    'class' => 'inputFormEnter'
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
                'data_class' => 'Sto\CoreBundle\Entity\CompanySpecialization'
            ]);
    }

    public function getName()
    {
        return 'sto_company_specialization';
    }
}
