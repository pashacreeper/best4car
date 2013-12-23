<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class CompanyEmailsAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('email')
            ->add('description')
        ;
    }

}
