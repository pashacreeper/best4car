<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CompanyGalleryAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'StoCoreBundle:Admin:edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('imageName')
            ->add('visible')
            ->add('updatedAt')
            ->add('company')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array(
                'required' => true
            ))
            ->add('image', 'file', array(
                'required' => false,
                'attr' => [
                    'class' => 'image'
                ]
            ))
            ->add('visible')
            ->add('updatedAt')
            ->add('company')
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('image', null, ['template' => 'StoCoreBundle:Admin:list_image.html.twig'])
            ->addIdentifier('name')
            ->add('company')
            ->add('visible')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('imageName')
            ->add('visible')
            ->add('updatedAt')
            ->add('company')
        ;
    }

    public function preUpdate($object)
    {
        $now = new \DateTime('now');
        $object->setUpdatedAt($now);
    }

}
