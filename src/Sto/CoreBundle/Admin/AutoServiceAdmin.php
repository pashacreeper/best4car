<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Doctrine\ORM\EntityRepository;

class AutoServiceAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('shortName')
            ->add('name')
            ->add('parent')
            ->add('children')
            ->add('position')
            ->add('code')
            ->add('companyType')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('shortName')
            ->add('name')
            ->add('parent')
            ->add('position')
            ->add('code')
            ->add('companyType', null, array(
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('company_type')
                        ->where('company_type.parent is null');
                },
            ))
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('name')
            ->addIdentifier('parent')
            ->add('companyTypeName')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('shortName')
            ->add('name')
            ->add('position')
            ->add('code')
            ->add('companyType')
            ->add('parent')
        ;
    }
}
