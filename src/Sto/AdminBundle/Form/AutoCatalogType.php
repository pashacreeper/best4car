<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AutoCatalogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'dict.fields.name'))
            ->add('visible', null, array('label' => 'Видимость'))
            ->add('parent', null, array('label' => 'dict.fields.parent'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sto\CoreBundle\Entity\AutoCatalog'
        ));
    }

    public function getName()
    {
        return 'sto_adminbundle_autocatalogtype';
    }
}
