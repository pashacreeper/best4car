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
                    'class' => 'styled span2'
                ],
                'empty_value' => 'все виды',
                'choices' => SubscriptionType::getOptions(),
                'required' => false,
            ])
            ->add('mark', 'entity', [
                'class' => 'StoCoreBundle:Mark',
                'choices' => $options['userMarks'],
                'label' => 'Марки автомобилей',
                'attr' => [
                    'class' => 'styled span3',
                ],
                'empty_value' => 'все, на которые подписан',
                'required' => false,
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'userMarks' => []
        ]);
    }

    public function getName()
    {
        return 'sto_content_feed';
    }
}
