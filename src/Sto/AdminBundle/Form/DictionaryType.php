<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DictionaryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'dict.fields.name'))
            // ->add('parentId')
            ->add('parent', null, array('label' => 'dict.fields.parent'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sto\CoreBundle\Entity\Dictionary'
        ));
    }

    public function getName()
    {
        return 'sto_adminbundle_dictionarytype';
    }
}
