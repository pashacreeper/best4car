<?php
namespace Sto\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sto\ContentBundle\Form\Extension\ChoiceList\FeedbackFilter;

class FeedbackFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filter', 'choice', [
                'choice_list' => new FeedbackFilter(),
                'attr' => [
                    'class' => 'styled1'
                ]
            ])
        ;
    }

    public function getName()
    {
        return 'sto_feedback_filter';
    }
}
