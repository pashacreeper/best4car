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
            ->add('content', 'genemu_tinymce', [
                'label' => 'Отзыв',
                'required' => false,
                'render_optional_text' => false,
                'configs' => [
                    'entity_encoding' => "raw",
                    'language' => 'ru',
                    'menubar' => false,
                    'statusbar' => false,
                    'resize' => false,
                    'width' =>'100%',
                    'height' => 150
                ],
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
            ->add('statenumber', null, [
                'label' => 'Гос. номер автомобиля',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('orderNumber', null, [
                'label' => 'Номер заказа-наряда',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('feedbackRating', null, [
                'label' => 'Оценка отзыва'
            ])
            ->add('published', null, [
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
