<?php

namespace Sto\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class UserContactsAdmin extends Admin
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
