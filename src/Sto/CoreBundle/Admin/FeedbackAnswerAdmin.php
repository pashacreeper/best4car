<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class FeedbackAnswerAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('answer')
            ->add('date')
            ->add('owner')
            ->add('feedback')
            ->add('complain')
            ->add('hidden')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('answer')
            ->add('date')
            ->add('owner')
            ->add('feedback')
            ->add('complain')
            ->add('hidden')
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('answer')
            ->addIdentifier('feedback')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('answer')
            ->add('date')
            ->add('owner')
            ->add('feedback')
            ->add('complain')
            ->add('hidden')
        ;
    }
}