<?php

namespace Sto\AdminBundle\Form\CatalogType;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Modification extends Base
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'label' => 'Модификация'
            ])
            ->add('uri', 'url', [
                'label' => 'URL',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('parent', null, [
                'label' => 'Модель',
                'required' => true,
                'disabled' => true
            ])
            ->add('numberOfDoors', null, [
                'label' => 'Кол-во дверей',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('engine', null, [
                'label' => 'Объем двигат.',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('power', null, [
                'label' => 'Мощность',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('fullSpeed', null, [
                'label' => 'Макс. скорость',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('bodyType', null, [
                'label' => 'Тип кузова',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('startOfProduction', null, [
                'label' => 'Начало выпуска',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('closingOfProduction', null, [
                'label' => 'Оконч. выпуска',
                'required' => false,
                'render_optional_text' => false,
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
            'data_class' => 'Sto\CoreBundle\Entity\Catalog\Modification'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_auto_catalog_modification';
    }
}
