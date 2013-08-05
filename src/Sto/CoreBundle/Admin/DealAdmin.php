<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class DealAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('company')
            ->add('services')
            ->add('imageName')
            ->add('imageName2')
            ->add('imageName3')
            ->add('terms')
            ->add('startDate')
            ->add('endDate')
            ->add('startTime')
            ->add('endTime')
            ->add('place')
            ->add('gps')
            ->add('contactInformation')
            ->add('type')
            ->add('updatedAt')
            ->add('draft')
            ->add('auto')
            ->add('autoServices')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array(
                'required' => true
            ))
            ->add('company')
            ->add('services')
            ->add('image', 'file')
            ->add('image2', 'file')
            ->add('image3', 'file')
            ->add('terms')
            ->add('startDate', 'date')
            ->add('endDate', 'date')
            ->add('startTime', 'time')
            ->add('endTime', 'time')
            ->add('place')
            ->add('gps')
            ->add('contactInformation')
            ->add('type')
            ->add('draft')
            ->add('auto')
            ->add('autoServices')
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('name')
            ->add('company')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('company')
            ->add('services')
            ->add('imageName')
            ->add('imageName2')
            ->add('imageName3')
            ->add('terms')
            ->add('startDate')
            ->add('endDate')
            ->add('startTime')
            ->add('endTime')
            ->add('place')
            ->add('gps')
            ->add('contactInformation')
            ->add('type')
            ->add('updatedAt')
            ->add('draft')
            ->add('auto')
            ->add('autoServices')
        ;
    }
}
