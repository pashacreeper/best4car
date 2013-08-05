<?php

namespace Sto\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class RatingGroupAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('minRating')
            ->add('maxRating')
            ->add('users')
            ->add('multiplier')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('minRating')
            ->add('maxRating')
            ->add('multiplier')
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('name')
            ->add('minRating')
            ->add('maxRating')
            ->add('multiplier')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('minRating')
            ->add('maxRating')
            ->add('multiplier')
        ;
    }
}