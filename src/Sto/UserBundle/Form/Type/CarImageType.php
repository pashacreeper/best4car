<?php

namespace Sto\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CarImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', 'file', [
                'label' => false,
                'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                'property_path' => 'image',
                'required' => false,
                'attr' => [
                    'data-image' => 'image',
                ],
                'constraints' => [
                    new Assert\Image()
                ]
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
            'data_class' => 'Sto\UserBundle\Entity\UserCarImage',
        ]);
    }

    public function getName()
    {
        return 'sto_user_car_image';
    }
}
