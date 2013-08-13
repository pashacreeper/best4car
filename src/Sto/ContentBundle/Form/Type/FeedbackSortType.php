<?php
namespace Sto\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sto\ContentBundle\Form\Extension\ChoiceList\FeedbackSort;

class FeedbackSortType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sort', 'choice', [
                'choice_list' => new FeedbackSort(),
                'attr' => [
                    'class' => 'styled1'
                ]
            ])
        ;
    }

    public function getName()
    {
        return 'sto_feedback_sort';
    }
}
