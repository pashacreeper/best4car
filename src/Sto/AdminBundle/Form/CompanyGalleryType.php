<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompanyGalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', 'file', [
                'label' => false,
                'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                'property_path' => 'image',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'data-image' => 'image',
                    'class' => 'photoFileInput'
                ]
            ])
            ->add('name', null, [
                'label' => false,
                'attr' => [
                    'class' => 'nameInput',
                    'placeholder' => 'Описание фотографии'
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\CompanyGallery'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_company_gallery';
    }
}
