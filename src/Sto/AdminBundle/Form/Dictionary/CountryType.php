<?php

namespace Sto\AdminBundle\Form\Dictionary;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CountryType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('shortName', null, [
                'label' => 'dict.fields.code',
                'render_optional_text' => false
            ])
            ->add('icon', null, [
                'label' => 'city.fields.icon',
                'render_optional_text' => false,
            ])
            ->add('image', null, [
                'label' => 'city.fields.image',
                'render_optional_text' => false,
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\Dictionary\Country'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_dictionary_country';
    }
}
