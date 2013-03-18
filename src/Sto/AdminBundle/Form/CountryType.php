<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CountryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'country.fields.name'
            ])
            ->add('code', null, [
                'label' => 'country.fields.code'
            ])
            ->add('icon', null, [
                'label' => 'country.fields.icon'
            ])
            ->add('image', null, [
                'label' => 'country.fields.image'
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\Country'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_country';
    }
}
