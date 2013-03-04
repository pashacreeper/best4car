<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RatingGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Название'
            ])
            ->add('minRating', null, [
                'label' => 'Минимальное значение рейтинга'
            ])
            ->add('maxRating', null, [
                'label' => 'Максимальное значение рейтинга'
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\UserBundle\Entity\RatingGroup',
        ]);
    }

    public function getName()
    {
        return 'sto_admin_ratinggroup';
    }
}
