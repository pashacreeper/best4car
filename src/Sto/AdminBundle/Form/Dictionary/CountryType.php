<?php

namespace Sto\AdminBundle\Form\Dictionary;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

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
                'attr' => [
                    'data-image' => 'icon',
                ]
            ])
            ->add('image', null, [
                'label' => 'city.fields.image',
                'render_optional_text' => false,
                'attr' => [
                    'data-image' => 'image',
                ]
            ])
            ->add('parent', 'entity', [
                'label' => 'dict.fields.parent',
                'required' => false,
                'render_optional_text' => false,
                'empty_value' => "Выберите страну",
                'class' => 'StoCoreBundle:Country',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('country')
                        ->where('country.parent is null')
                        ->orderBy('country.name', 'ASC');
                },
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\Country'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_dictionary_country';
    }
}
