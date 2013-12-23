<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class CompanyPhonesAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('phone')
            ->add('description')
        ;
    }

    public function prePersist($object)
    {
        var_dump($object); die;
    }
}
