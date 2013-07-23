<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Doctrine\ORM\EntityRepository;

class FeedbackCompanyType extends FeedbackType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('car', null, [
                'label' => 'Автомобиль',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'inputField span4'
                ]
            ])
            ->add('mastername', null, [
                'label' => 'Имя мастера',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'inputField span4'
                ]
            ])
            ->add('statenumber', null, [
                'label' => 'Гос. номер авто',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'inputField span4'
                ]
            ])
            ->add('orderNumber', null, [
                'label' => 'Номер заказа-наряда',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'inputField span4'
                ]
            ])
            ->add('priceLevel', null, [
                'label' => 'Уровень цен',
                'class' => '\Sto\CoreBundle\Entity\Dictionary\PriceLevel',
                'expanded' => true,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u');
                },
                'required' => true,
                'empty_value' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => 'priceLevelMarker'
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\FeedbackCompany'
        ]);
    }
}
