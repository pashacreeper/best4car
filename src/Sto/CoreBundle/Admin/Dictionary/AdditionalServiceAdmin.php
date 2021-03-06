<?php

namespace Sto\CoreBundle\Admin\Dictionary;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class AdditionalServiceAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    protected $datagridValues = array(
        '_sort_order'   => 'ASC',
        '_sort_by'      => 'position'
    );

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('shortName')
            ->add('name')
            ->add('position')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('shortName')
            ->add('name')
            ->add('position')
            ->add('iconMap', 'file', [
                'required' => false
            ])
            ->add('iconSmall', 'file', [
                'required' => false
            ])
            ->add('iconLarge', 'file', [
                'required' => false
            ])
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('shortName')
            ->addIdentifier('name')
            ->addIdentifier('position')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('shortName')
            ->add('name')
            ->add('position')
        ;
    }
}
