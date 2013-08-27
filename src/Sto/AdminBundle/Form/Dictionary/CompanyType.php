<?php

namespace Sto\AdminBundle\Form\Dictionary;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CompanyType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('iconMap', null, [
                'label' => 'dict.icon.map',
                'render_optional_text' => false,
                'attr' => [
                    'data-image' => 'iconMap',
                ]
            ])
            ->add('iconLarge', null, [
                'label' => 'dict.icon.large',
                'render_optional_text' => false,
                'attr' => [
                    'data-image' => 'iconLarge',
                ]
            ])
            ->add('iconSmall', null, [
                'label' => 'dict.icon.small',
                'render_optional_text' => false,
                'attr' => [
                    'data-image' => 'iconSmall',
                ]
            ])
            ->add('parent', 'entity', [
                'label' => 'dict.fields.parent',
                'required' => false,
                'render_optional_text' => false,
                'class' => 'StoCoreBundle:CompanyType',
                'empty_value' => "Choise parent",
                'empty_data' => null,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('company')
                        ->where('company.parent is null')
                        ->orderBy('company.name', 'ASC')
                    ;
                },
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\CompanyType'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_dictionary_company';
    }
}
