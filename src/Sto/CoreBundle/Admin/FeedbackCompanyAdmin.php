<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class FeedbackCompanyAdmin extends Admin
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
            ->add('priceLevel')
            ->add('companyRating')
            ->add('company')
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
            ->add('priceLevel')
            ->add('companyRating')
            ->add('company')
            ->add('feedbackAnswer', 'sonata_type_model')
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('content')
            ->addIdentifier('company')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('company.city')
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
            ->add('priceLevel')
            ->add('companyRating')
            ->add('company')
        ;
    }
}
