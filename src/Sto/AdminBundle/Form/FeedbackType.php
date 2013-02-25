<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', null, [
                'label' => 'Отзыв'
            ])
            ->add('visitDate', 'date', [
                'label' => 'Дата посещения',
                'widget' => 'single_text',
                'datepicker' => true,
            ])
            ->add('mastername', null, [
                'label' => 'Имя мастера',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('car', null, [
                'label' => 'Автомобиль',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('gn', null, [
                'label' => 'Гос. номер автомобиля',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('nn', null, [
                'label' => 'Номер заказа-наряда',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('comapnyRating', null, [
                'label' => 'Оценка компании'
            ])
            ->add('feedbackRating', null, [
                'label' => 'Оценка отзыва'
            ])
            ->add('targetRating', null, [
                'label' => 'Что оцениваем'
            ])
            ->add('isPublished', null, [
                'label' => 'Публикация',
                'required' => false,
                'render_optional_text' => false
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\Feedback'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_feedback';
    }
}
