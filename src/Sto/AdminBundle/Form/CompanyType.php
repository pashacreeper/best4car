<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('slogan')
            ->add('fullName')
            ->add('web')
            ->add('specialization')
            ->add('services')
            ->add('additionalServices')
            ->add('logo')
            ->add('workingTime')
            ->add('phones')
            ->add('skype')
            ->add('email')
            ->add('address')
            ->add('gps')
            ->add('createtDate')
            ->add('photos')
            ->add('socialNetworks')
            ->add('rating')
            ->add('reviews')
            ->add('deals')
            ->add('description')
            ->add('subscribable')
            ->add('hourPrice')
            ->add('managers')
            ->add('administratorContactInfo')
            ->add('visible')
            ->add('notes')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sto\CoreBundle\Entity\Company'
        ));
    }

    public function getName()
    {
        return 'sto_adminbundle_companytype';
    }
}
