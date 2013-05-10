<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FeedbackCompanyType extends FeedbackType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('visitDate', 'date', [
                'label' => 'Дата посещения',
                'widget' => 'single_text',
                'datepicker' => true,
            ])
            ->add('car', null, [
                'label' => 'Автомобиль',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('mastername', null, [
                'label' => 'Имя мастера',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('statenumber', null, [
                'label' => 'Гос. номер авто',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('orderNumber', null, [
                'label' => 'Номер заказа-наряда',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('priceLevel', null, [
                'label' => 'Уровень цен',
                'required' => false,
                'render_optional_text' => false
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\FeedbackCompany'
        ]);
    }
}
