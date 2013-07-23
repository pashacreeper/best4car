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
                'render_optional_text' => false,
                'required' => false,
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
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => [
                    'class' => 'visitDate',
                    'data-format' => 'dd-MM-yyyy',
                ],
            ])
            ->add('mastername', null, [
                'label' => 'Фамилия мастера',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => "inputField span4"
                ]
            ])
            ->add('car', null, [
                'label' => 'Автомобиль',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => "inputField span4"
                ]
            ])
            ->add('statenumber', null, [
                'label' => 'Гос. номер',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => "inputField span4"
                ]
            ])
            ->add('orderNumber', null, [
                'label' => 'Номер заказа',
                'required' => false,
                'render_optional_text' => false,
                'attr' => [
                    'class' => "inputField span4"
                ]
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
