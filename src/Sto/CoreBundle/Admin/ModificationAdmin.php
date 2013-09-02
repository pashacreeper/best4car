<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ModificationAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('parent')
            ->add('uri')
            ->add('visible')
            ->add('numberOfDoors')
            ->add('engine')
            ->add('power')
            ->add('fullSpeed')
            ->add('bodyType')
            ->add('startOfProduction')
            ->add('closingOfProduction')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array(
                'required' => true
            ))
            ->add('parent', null, array(
                'required' => true
            ))
            ->add('uri')
            ->add('visible', null, array(
                'required' => false
            ))
            ->add('numberOfDoors')
            ->add('engine')
            ->add('power')
            ->add('fullSpeed')
            ->add('bodyType')
            ->add('startOfProduction')
            ->add('closingOfProduction')
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('name')
            ->add('parent')
            ->add('uri')
            ->add('visible')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('parent')
            ->add('uri')
            ->add('visible')
            ->add('numberOfDoors')
            ->add('engine')
            ->add('power')
            ->add('fullSpeed')
            ->add('bodyType')
            ->add('startOfProduction')
            ->add('closingOfProduction')
        ;
    }
}
