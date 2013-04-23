<?php

namespace Sto\AdminBundle\Form\CatalogType;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Model extends Base
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'Модель'
            ])
            ->add('uri', 'url', [
                'label' => 'URL',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('parent', null, [
                'label' => 'Марка',
                'required' => true,
                'disabled' => true
            ])
            ->add('visible', null, [
                'required' => false,
                'render_optional_text' => false,
                'label' => 'Отображать'
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\Catalog\Model'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_auto_catalog_model';
    }
}
