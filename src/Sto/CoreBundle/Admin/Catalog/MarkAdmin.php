<?php

namespace Sto\CoreBundle\Admin\Catalog;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class MarkAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('children')
            ->add('uri')
            ->add('visible')
            ->add('iconName')
            ->add('updatedAt')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array(
                'required' => true
            ))
            ->add('uri')
            ->add('visible')
            ->add('icon', 'file')
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('icon', null, ['template' => 'StoCoreBundle:Admin:list_image.html.twig'])
            ->addIdentifier('name')
            ->add('uri')
            ->add('visible')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('uri')
            ->add('visible')
            ->add('updatedAt')
        ;
    }
}
