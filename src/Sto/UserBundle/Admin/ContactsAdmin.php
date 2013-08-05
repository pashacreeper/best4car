<?php

namespace Sto\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ContactsAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('value')
            ->add('user')
            ->add('company')
            ->add('type')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('value')
            ->add('user')
            ->add('company')
            ->add('type')
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('value')
            ->addIdentifier('user')
            ->addIdentifier('company')
            ->addIdentifier('type')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('value')
            ->add('user')
            ->add('company')
            ->add('type')
        ;
    }
}