<?php
namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CompanyAutoServiceAdmin extends Admin
{
    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('service')
            ->add('specialization')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('specialization', null, [
                'required' => true,
                'attr' => [
                    'class' => 'auto_specialization_select'
                ],
            ])
            ->add('parent', null, [
                'required' => false,
            ])
            ->add('service', null, [
                'required' => true,
                'group_by' => 'companyType.id',
                'attr' => [
                    'class' => 'auto_service_select'
                ],
            ])
        ;
    }
}
