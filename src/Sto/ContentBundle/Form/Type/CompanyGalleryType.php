<?php
namespace Sto\ContentBundle\Form\Type;

use Sto\ContentBundle\Form\CompanyGalleryType as Gallery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CompanyGalleryType extends AbstractType
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
                'error_bubbling' => false,
            ))
        ;
    }

    public function getName()
    {
        return 'sto_company_reguister_gallery';
    }
}
