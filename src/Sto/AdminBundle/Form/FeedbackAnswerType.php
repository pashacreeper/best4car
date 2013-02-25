<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FeedbackAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('answer', null, array('label'=>'Ответ'))
            //->add('date')
            //->add('managerId')
            //->add('feedbackId')
            //->add('manager')
            //->add('feedback')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sto\CoreBundle\Entity\FeedbackAnswer',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'sto_adminbundle_feedbackanswertype';
    }
}
