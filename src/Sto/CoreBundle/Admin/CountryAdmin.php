<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CountryAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('shortName')
            ->add('name')
            ->add('iconName')
            ->add('imageName')
            ->add('updatedAt')
            ->add('gps')
            ->add('parent')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('shortName')
            ->add('name')
            ->add('icon', 'file', [
                'required' => false,
            ])
            ->add('image', 'file', [
                'required' => false,
            ])
            ->add('updatedAt', null, [
                'required' => false,
            ])
            ->add('gps')
            ->add('parent', null, [
                'required' => false,
            ])
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('name')
            ->addIdentifier('shortName')
            ->addIdentifier('parent')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('shortName')
            ->add('name')
            ->add('iconName')
            ->add('imageName')
            ->add('updatedAt')
            ->add('gps')
            ->add('parent')
        ;
    }
}
