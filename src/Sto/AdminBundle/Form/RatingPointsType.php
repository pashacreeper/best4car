<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RatingPointsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pointName', null, [
                'label' => 'Название',
                'attr' => [
                    'readonly'=>'readonly',
                ]
            ])
            ->add('description', null, [
                'label' => 'Описание'
            ])
            ->add('value', 'number', [
                'label' => 'Значение показателя'
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\RatingPoints',
        ]);
    }

    public function getName()
    {
        return 'sto_admin_ratingpoints';
    }
}
