<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sto\ContentBundle\Form\Extension\ChoiceList\SubscriptionType;

class FeedFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', [
                'label' => 'Виды уведомлений',
                'attr' => [
                    'class' => 'inputField span2'
                ],
                'empty_value' => 'все виды',
                'choices' => SubscriptionType::getOptions(),
                'required' => false,
            ])
            ->add('marks', 'entity', [
                'class' => 'StoCoreBundle:Mark',
                'label' => 'Марки автомобилей',
                'attr' => [
                    'class' => 'inputField span3 chzn-select',
                    'data-placeholder' => 'все, на которые подписан',
                ],
                'multiple' => true,
                'empty_value' => 'все, на которые подписан',
                'required' => false,
            ])
        ;
    }

    public function getName()
    {
        return 'sto_content_feed';
    }
}
