<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CountryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'country.fields.name'))
            ->add('code', null, array('label' => 'country.fields.code'))
            ->add('icon', null, array('label' => 'country.fields.icon'))
            ->add('image', null, array('label' => 'country.fields.image'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sto\CoreBundle\Entity\Country'
        ));
    }

    public function getName()
    {
        return 'sto_adminbundle_countrytype';
    }
}
