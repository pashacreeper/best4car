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
            ->add('description')
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
            ->add('contactInformationName')
            ->add('contactInformationPhone')
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
            ->add('description')
            ->add('company')
            ->add('services', null, array(
                'required' => false
            ))
            ->add('image', 'file', array(
                'required' => false
            ))
            ->add('image2', 'file', array(
                'required' => false
            ))
            ->add('image3', 'file', array(
                'required' => false
            ))
            ->add('terms')
            ->add('startDate', 'date')
            ->add('endDate', 'date')
            ->add('startTime', 'time')
            ->add('endTime', 'time')
            ->add('place')
            ->add('gps')
            ->add('contactInformationName')
            ->add('contactInformationPhone')
            ->add('type')
            ->add('draft', null, array(
                'required' => false
            ))
            ->add('is_vip', null, [
                'required' => false,
            ])
            ->add('auto', null, array(
                'required' => false
            ))
            ->add('autoServices', null, array(
                'required' => false
            ))
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('image', null, ['template' => 'StoCoreBundle:Admin:list_image.html.twig'])
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
            ->add('contactInformationName')
            ->add('contactInformationPhone')
            ->add('type')
            ->add('updatedAt')
            ->add('draft')
            ->add('auto')
            ->add('autoServices')
        ;
    }
}
