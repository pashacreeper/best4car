<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'city.fields.name'
            ])
            ->add('code', null, [
                'label' => 'city.fields.code'
            ])
            ->add('icon', null, [
                'label' => 'city.fields.icon'
            ])
            ->add('image', null, [
                'label' => 'city.fields.image'
            ])
            ->add('country', null, [
                'label' => 'city.fields.country'
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\DictionaryCity'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_city';
    }
}
