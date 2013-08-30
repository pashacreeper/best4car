<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CompanyAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('slogan')
            ->add('fullName')
            ->add('web')
            ->add('specialization')
            ->add('services')
            ->add('additionalServices')
            ->add('autoServices')
            ->add('logoName')
            // ->add('workingTime')
            // ->add('phones')
            ->add('skype')
            ->add('email')
            ->add('address')
            ->add('gps')
            ->add('createtDate')
            ->add('photos')
            ->add('socialNetworks')
            ->add('rating')
            ->add('reviews')
            ->add('deals')
            ->add('description')
            ->add('subscribable')
            ->add('hourPrice')
            ->add('currency')
            ->add('administratorContactInfo')
            ->add('visible')
            ->add('notes')
            ->add('groups')
            ->add('gallery')
            ->add('updatedAt')
            ->add('city')
            ->add('autos')
            ->add('linkVK')
            ->add('linkTW')
            ->add('linkFB')
            ->add('companyManager')
            ->add('contacts')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array(
                'required' => true
            ))
            ->add('slogan')
            ->add('fullName')
            ->add('web')
            ->add('specialization', null, array(
                'required' => false,
            ))
            ->add('services', null, array(
                'required' => false,
            ))
            ->add('additionalServices', null, array(
                'required' => false,
            ))
            ->add('autoServices', null, array(
                'required' => false,
            ))
            ->add('logo', 'file', array(
                'required' => false,
            ))
            // ->add('workingTime')
            // ->add('phones')
            ->add('skype')
            ->add('email')
            ->add('address')
            ->add('gps')
            ->add('createtDate', 'date')
            ->add('photos')
            ->add('socialNetworks')
            ->add('rating')
            ->add('reviews')
            ->add('description')
            ->add('subscribable', null, array(
                'required' => false,
            ))
            ->add('hourPrice')
            ->add('currency')
            ->add('administratorContactInfo')
            ->add('visible', null, array(
                'required' => false,
            ))
            ->add('notes')
            ->add('groups', null, array(
                'required' => false,
            ))
            ->add('updatedAt')
            ->add('city')
            ->add('autos', null, array(
                'required' => false,
            ))
            ->add('linkVK')
            ->add('linkTW')
            ->add('linkFB')
            ->add('contacts', 'sonata_type_collection', [
                    'by_reference' => false,
                ],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                ]
            )
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('logo', null, ['template' => 'StoCoreBundle:Admin:list_image.html.twig'])
            ->addIdentifier('name')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('slogan')
            ->add('fullName')
            ->add('web')
            ->add('specialization')
            ->add('services')
            ->add('additionalServices')
            ->add('autoServices')
            ->add('logoName')
            ->add('workingTime')
            ->add('phones')
            ->add('skype')
            ->add('email')
            ->add('address')
            ->add('gps')
            ->add('createtDate')
            ->add('photos')
            ->add('socialNetworks')
            ->add('rating')
            ->add('reviews')
            ->add('description')
            ->add('subscribable')
            ->add('hourPrice')
            ->add('currency')
            ->add('administratorContactInfo')
            ->add('visible')
            ->add('notes')
            ->add('groups')
            ->add('gallery')
            ->add('updatedAt')
            ->add('city')
            ->add('autos')
            ->add('linkVK')
            ->add('linkTW')
            ->add('linkFB')
            ->add('companyManager')
            ->add('contacts')
        ;
    }
}
