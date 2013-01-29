<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\Translator;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', null, array('label' => 'city.fields.name'))
            ->add('code', null, array('label' => 'city.fields.code'))
            ->add('icon', null, array('label' => 'city.fields.icon'))
            ->add('image', null, array('label' => 'city.fields.image'))
            // ->add('countryId')
            ->add('country', null, array('label' => 'city.fields.country'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sto\CoreBundle\Entity\City'
        ));
    }

    public function getName()
    {
        return 'sto_adminbundle_citytype';
    }
}
