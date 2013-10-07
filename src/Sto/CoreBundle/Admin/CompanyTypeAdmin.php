<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CompanyTypeAdmin extends Admin
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
            ->add('iconNameMap')
            ->add('iconNameMapSelected')
            ->add('iconNameCompanyCard')
            ->add('updatedAt')
            ->add('autoServices')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Тип компании')
                ->add('shortName')
                ->add('name')
                ->add('parent')
                ->add('position')
                ->add('iconMap', 'file', [
                    'required' => false,
                ])
                ->add('iconMapSelected', 'file', [
                    'required' => false,
                ])
                ->add('iconCompanyCard', 'file', [
                    'required' => false,
                ])
                ->add('updatedAt')
            ->end()
            ->with('Услуги')
                ->add('autoServices', null, [
                    'attr' => [
                        'class' => 'admin-service-select',
                    ],
                ])
            ->end()
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('shortName')
            ->addIdentifier('name')
            ->addIdentifier('parent')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('shortName')
            ->add('name')
            ->add('position')
            ->add('parent')
            ->add('iconNameMap')
            ->add('iconNameMapSelected')
            ->add('iconNameCompanyCard')
            ->add('updatedAt')
            ->add('autoServices')
        ;
    }
}
