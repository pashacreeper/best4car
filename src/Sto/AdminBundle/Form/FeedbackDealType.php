<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FeedbackDealType extends AbstractType
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
            ->add('deal', null, [
                'label' => 'Акция',
                'required' => false,
                'render_optional_text' => false,
            ])
            ->add('published', null, [
                'label' => 'Публикация',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('currencyLevel', 'entity', [
                'label' => 'Оценка уровня цен',
                'required' => false,
                'render_optional_text' => false,
                'class' => 'StoCoreBundle:Dictionary',
                'query_builder' => function (\Sto\CoreBundle\Repository\DictionaryRepository $repository) {
                         return $repository->createQueryBuilder('s')
                                ->where('s.parentId = ?1')
                                ->setParameter(1, 5);
                     }
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\FeedbackDeal'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_feedback';
    }
}
