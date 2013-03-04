<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('password')
            ->add('enabled')
            ->add('groups')
            //->add('roles')
            ->add('firstName')
            ->add('lastName')
            ->add('rating')
            ->add('ratingGroup')
            ->add('phoneNumber')
            ->add('avatarUrl')
            ->add('birthDate')
            ->add('gender')
            ->add('cityId')
            ->add('linkVK')
            ->add('linkFB')
            ->add('linkGP')
            ->add('autoProfilesLinks')
            ->add('linkGarage')
            ->add('contentGroupId')
            ->add('description')
            ->add('jobId')

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sto\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'sto_adminbundle_usertype';
    }
}
