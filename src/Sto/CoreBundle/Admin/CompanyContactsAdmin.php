<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class CompanyContactsAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('type')
            ->add('value')
        ;
    }

}
