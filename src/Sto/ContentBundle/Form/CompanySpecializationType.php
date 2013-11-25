<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\NotBlank;

class CompanySpecializationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', null, [
                'label' => false,
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('company_type')
                        ->where('company_type.parent is null');
                },
                'empty_value' => ' ',
                'attr' => [
                    'class' => 'inputFormEnter companySpecialization'
                ],
            ])
            ->add('subType', 'shtumi_dependent_filtered_entity', [
                'label' => false,
                'required' => true,
                'entity_alias' => 'subType_by_type',
                'parent_field' => 'type',
                'attr' => [
                    'class' => 'inputFormEnter'
                ],
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
