<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserGalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', 'file', [
                'label' => 'Выберите фотографию',
                'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                'property_path' => 'image',
                'required' => false,
                'attr' => [
                    'data-image' => 'image',
                ]
            ])
            ->add('name', null, [
                'label' => 'Назовите фотографию',
                'attr' => [
                    'class' => 'span6'
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\UserBundle\Entity\UserGallery'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_user_gallery';
    }
}
