<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class FeedbackDealAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('content')
            ->add('visitDate')
            ->add('mastername')
            ->add('car')
            ->add('stateNumber')
            ->add('orderNumber')
            ->add('user')
            ->add('feedbackRating')
            ->add('pluses')
            ->add('minuses')
            ->add('targetRating')
            ->add('published')
            ->add('date')
            ->add('ip')
            ->add('evaluation')
            ->add('complain')
            ->add('hidden')
            ->add('dealRating')
            ->add('deal')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('content')
            ->add('visitDate')
            ->add('mastername')
            ->add('car')
            ->add('stateNumber')
            ->add('orderNumber')
            ->add('user')
            ->add('feedbackRating')
            ->add('pluses')
            ->add('minuses')
            ->add('targetRating')
            ->add('published')
            ->add('date')
            ->add('ip')
            // ->add('evaluation')
            ->add('complain')
            ->add('hidden')
            ->add('dealRating')
            ->add('deal')
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('content')
            ->addIdentifier('deal')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('content')
            ->add('visitDate')
            ->add('mastername')
            ->add('car')
            ->add('stateNumber')
            ->add('orderNumber')
            ->add('user')
            ->add('feedbackRating')
            ->add('pluses')
            ->add('minuses')
            ->add('targetRating')
            ->add('published')
            ->add('date')
            ->add('ip')
            ->add('evaluation')
            ->add('complain')
            ->add('hidden')
            ->add('dealRating')
            ->add('deal')
        ;
    }
}
