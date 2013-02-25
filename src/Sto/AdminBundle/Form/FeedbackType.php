<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sto\AdminBundle\Form\FeedbackAnswerType;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', null, array('label'=>'Отзыв'))
            ->add('visitDate', null, array('label'=>'Дата посещения'))
            ->add('mastername', null, array('label'=>'Имя мастера', 'required'=>false))
            ->add('car', null, array('label'=>'Автомобиль', 'required'=>false))
            ->add('gn', null, array('label'=>'Гос. номер автомобиля', 'required'=>false))
            ->add('nn', null, array('label'=>'Номер заказа-наряда', 'required'=>false))
            //->add('userId')
            ->add('comapnyRating', null, array('label'=>'Оценка компании'))
            ->add('feedbackRating', null, array('label'=>'Оценка отзыва'))
            //->add('pluses')
            //->add('minuses')
            ->add('targetRating', null, array('label'=>'Что оцениваем'))
            ->add('isPublished', null, array('label'=>'Публикация', 'required'=>false))
            //->add('user', null, array('label'=>'Автор'))
            //->add('feedbackAnswer', new FeedbackAnswerType())

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sto\CoreBundle\Entity\Feedback'
        ));
    }

    public function getName()
    {
        return 'sto_adminbundle_feedbacktype';
    }
}
