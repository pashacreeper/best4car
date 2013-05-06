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
                    'height' => 150
                ],
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
