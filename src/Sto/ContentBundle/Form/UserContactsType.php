<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserContactsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', null, [
                'label' => 'Тип',
                    'attr' => [
                        'class' => 'select2'
                    ]
            ])
            ->add('value', 'text', [
                'label' => 'Значение',
                'attr' => [
                    'class' => 'select2'
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Sto\UserBundle\Entity\UserContacts'
            ]
        );
    }

    public function getName()
    {
        return 'sto_admin_contacts_user';
    }
}
