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
            ->add('parent', 'entity', array(
                'label' => 'dict.fields.parent',
                'class' => 'StoCoreBundle:AutoCatalogBody',
                'query_builder' => function($repository) { return $repository->createQueryBuilder('ab')
                    //->where('ab.discr = :discr')
                    //->setParameter('discr', 'autocatalog_body')
                    ->orderBy('ab.name', 'ASC'); },
                'property' => 'name',
            ))
            ->add('bodyType', null, [
                'label' => 'Тип кузова'
            ])
            ->add('engineVolume', null, [
                'label' => 'Объем двигателя'
            ])
            ->add('power', null, [
                'label' => 'Мощность'
            ])
            ->add('privod', null, [
                'label' => 'Привод'
            ])
            ->add('transmission', null, [
                'label' => 'Коробка передач'
            ])
            ->add('transmissionCount', null, [
                'label' => 'Кол-о передач'
            ])
            ->add('startProduction', null, [
                'label' => 'Начало выпуска'
            ])
            ->add('endProduction', null, [
                'label' => 'Окончание выпуска'
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
