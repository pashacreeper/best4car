<?php

namespace Sto\AdminBundle\Form\CatalogType;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Base extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'dict.fields.name'
            ])
            ->add('visible', null, [
                'label' => 'Отображать'
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\Catalog\Base'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_auto_catalog_base';
    }
}
