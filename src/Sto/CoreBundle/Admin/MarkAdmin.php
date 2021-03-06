<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class MarkAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'StoCoreBundle:Admin:edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

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
            ->add('name', null, [
                'required' => true
            ])
            ->add('uri')
            ->add('visible', null, [
                'required' => false,
            ])
            ->add('icon', 'file', [
                'required' => false
            ])
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('icon', null, ['template' => 'StoCoreBundle:Admin:list_image_original.html.twig'])
            ->addIdentifier('name')
            ->add('uri', null, ['template' => 'StoContentBundle:Admin:url_field.html.twig'])
            ->add('visible')
            ->add('children', null, ['template' => 'StoContentBundle:Admin:mark_models_list.html.twig', 'label' => 'Модели'])
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
