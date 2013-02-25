<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FeedbackAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('answer', 'textarea', [
                'label'=>'Ответ',
                'attr' => [
                    'rows' => 4,
                    'class' => 'span10'
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\FeedbackAnswer',
            'csrf_protection' => false,
        ]);
    }

    public function getName()
    {
        return 'sto_admin_feedbackanswer';
    }
}
