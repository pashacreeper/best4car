<?php

namespace Sto\AdminBundle\Form\Dictionary;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CityType extends BaseType
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
            ->add('parent', 'entity', [
                'label' => 'dict.fields.parent',
                'render_optional_text' => false,
                'class' => 'StoCoreBundle:Dictionary\Country',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('country')
                        ->orderBy('country.name', 'ASC');
                },
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\Dictionary\City'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_dictionary_city';
    }
}
