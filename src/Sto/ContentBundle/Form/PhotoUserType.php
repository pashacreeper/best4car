<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PhotoUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('avatar', 'file', [
                'label' => 'Аватар',
                'required' => false,
                'attr' => [
                    'data-image' => 'avatar',
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\UserBundle\Entity\User',
            'crf_token' => false,
            'validation_groups' => array('user')
        ]);
    }

    public function getName()
    {
        return 'sto_content_user_avatar';
    }
}
