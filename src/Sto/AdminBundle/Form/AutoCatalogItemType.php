<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AutoCatalogItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'dict.fields.name'
            ])
            ->add('visible', null, [
                'label' => 'Видимость'
            ])
            ->add('parent', null, [
                'label' => 'dict.fields.parent'
            ])
            ->add('bodyType', null, [
                'label' => 'dict.fields.parent'
            ])
            ->add('engineVolume', null, [
                'label' => 'dict.fields.parent'
            ])
            ->add('power', null, [
                'label' => 'dict.fields.parent'
            ])
            ->add('privod', null, [
                'label' => 'dict.fields.parent'
            ])
            ->add('transmission', null, [
                'label' => 'dict.fields.parent'
            ])
            ->add('transmissionCount', null, [
                'label' => 'dict.fields.parent'
            ])
            ->add('startProduction', null, [
                'label' => 'dict.fields.parent'
            ])
            ->add('endProduction', null, [
                'label' => 'dict.fields.parent'
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\AutoCatalogItem'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_autocatalog';
    }
}
