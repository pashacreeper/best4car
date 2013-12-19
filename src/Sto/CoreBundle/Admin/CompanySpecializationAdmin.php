<?php
namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Doctrine\ORM\EntityRepository;

class CompanySpecializationAdmin extends Admin
{
    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('company')
            ->add('type')
            ->add('subType')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('type', null, [
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('company_type')
                        ->where('company_type.parent is null');
                },
                'attr' => [
                    'class' => 'auto_specialization_select_company'
                ],
            ])
            ->add('subType', 'shtumi_dependent_filtered_entity', [
                'entity_alias' => 'subType_by_type',
                'parent_field' => 'type'
            ])
        ;
    }
}
