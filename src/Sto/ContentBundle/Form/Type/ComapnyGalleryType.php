<?php
namespace Sto\ContentBundle\Form\Type;
    
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sto\ContentBundle\Form\CompanyGalleryType as Gallery;

class ComapnyGalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gallery', 'collection', array(
                'label' => '',
                'type' => new Gallery(),
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ))
        ;
    }

    public function getName()
    {
        return 'sto_company_reguister_gallery';
    }
}
