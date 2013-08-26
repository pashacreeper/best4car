<?php

namespace Sto\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sto\UserBundle\Entity\User;

class UserAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('locked')
            ->add('expired')
            ->add('roles')
            ->add('firstName')
            ->add('rating')
            ->add('ratingBonus')
            ->add('ratingGroup')
            ->add('phoneNumber')
            ->add('avatarUrl')
            ->add('birthDate')
            ->add('gender')
            ->add('city')
            ->add('linkVK')
            ->add('vkontakteId')
            ->add('linkFB')
            ->add('linkGP')
            ->add('autoProfilesLinks')
            ->add('linkGarage')
            ->add('description')
            ->add('subscriptions')
            ->add('requests')
            ->add('groups')
            ->add('updatedAt')
            ->add('contacts')
            ->add('usingEmail')
            ->add('avatarVk')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('username')
            ->add('email')
            ->add('enabled', null, array(
                'required' => false,
            ))
            ->add('plainPassword', 'password', array('required' => false))
            ->add('locked', null, array(
                'required' => false,
            ))
            ->add('expired', null, array(
                'required' => false,
            ))
            ->add('roles', null, array(
                'required' => false,
            ))
            ->add('firstName')
            ->add('rating')
            ->add('ratingBonus')
            ->add('ratingGroup')
            ->add('phoneNumber')
            ->add('avatar', 'file', array(
                'required' => false,
            ))
            ->add('birthDate')
            ->add('gender', 'choice', array(
                'choices' => User::getGenders()
            ))
            ->add('city')
            ->add('linkVK')
            ->add('vkontakteId')
            ->add('linkFB')
            ->add('linkGP')
            ->add('autoProfilesLinks')
            ->add('linkGarage')
            ->add('description')
            ->add('subscriptions')
            ->add('requests')
            ->add('groups')
            ->add('usingEmail', null, array(
                'required' => false,
            ))
            ->add('avatarVk')
            ->add('contacts', 'sonata_type_collection', [
                'by_reference' => false,
            ], [
                'edit' => 'inline',
                'inline' => 'table',
            ])
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('avatar', null, ['template' => 'StoCoreBundle:Admin:list_image.html.twig'])
            ->addIdentifier('username')
            ->addIdentifier('email')
            ->add('enabled')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('locked')
            ->add('expired')
            ->add('roles')
            ->add('firstName')
            ->add('rating')
            ->add('ratingBonus')
            ->add('ratingGroup')
            ->add('phoneNumber')
            ->add('avatarUrl')
            ->add('birthDate')
            ->add('gender')
            ->add('city')
            ->add('linkVK')
            ->add('vkontakteId')
            ->add('linkFB')
            ->add('linkGP')
            ->add('autoProfilesLinks')
            ->add('linkGarage')
            ->add('description')
            ->add('subscriptions')
            ->add('requests')
            ->add('groups')
            ->add('updatedAt')
            ->add('usingEmail')
            ->add('avatarVk')
        ;
    }
}
