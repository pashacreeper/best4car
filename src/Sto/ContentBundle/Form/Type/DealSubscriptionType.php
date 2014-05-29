<?php

namespace Sto\ContentBundle\Form\Type;

use Sto\ContentBundle\Form\Extension\ChoiceList\SubscriptionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DealSubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'hidden', [
                'data'      => SubscriptionType::DEAL,
                'read_only' => true
            ])
            ->add('mark', 'entity', [
                'class' => 'Sto\CoreBundle\Entity\Mark'
            ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return '';
    }
}
