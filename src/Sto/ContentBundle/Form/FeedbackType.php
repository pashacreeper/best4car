<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('feedbackRating', 'genemu_jqueryrating', [
                'label' => 'Ваша оценка'
            ])
            ->add('content', 'genemu_tinymce', [
                'label' => 'Текст отзыва',
                'required' => false,
                'render_optional_text' => false,
                'configs' => [
                    'entity_encoding' => "raw",
                    'language' => 'ru',
                    'menubar' => false,
                    'statusbar' => false,
                    'resize' => false,
                    'width' =>'100%',
                    'height' => 250,
                    'plugins' => ['image', 'link', 'code', 'paste', 'emoticons'],
                    'toolbar1' => 'undo redo | bold italic underline | bullist numlist | link image ',
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
            ->add('feedbackRating', 'genemu_jqueryrating', [
                'label' => 'Оценка отзыва'
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
        return 'sto_content_feedback';
    }
}
