<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyGalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', 'file', [
                'label' => false,
                'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                'property_path' => 'image',
                'required' => true,
                'attr' => [
                    'data-image' => 'image',
                    'class' => 'photoFileInput'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Не выбрана фотография для загрузки']),
                    new Assert\Image()
                ]
            ])
            ->add('name', null, [
                'label' => false,
                'attr' => [
                    'class' => 'nameInput',
                    'placeholder' => 'Описание фотографии'
                ]
            ])
            ->add('visible', 'hidden', [
                'label' => false,
                'data' => true,
            ])
            ->add('imageName', 'hidden', [
                'attr' => [
                    'class' => 'imageName'
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
        return 'sto_company_gallery';
    }
}
