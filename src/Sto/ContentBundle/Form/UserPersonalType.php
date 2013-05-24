<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserPersonalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, [
                'label' => 'Имя'
            ])
            ->add('lastName', null, [
                'label' => 'Фамилия'
            ])
            ->add('username', null, [
                'label' => 'Псевдоним'
            ])
            ->add('birthDate', 'date', [
                'label' => 'Возраст'
            ])
            /*->add('city', 'entity', [
                'label' => 'Город',
                'class' => 'StoCoreBundle:Dictionary\Country',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('country')
                        ->where('country.parent is not null')
                    ;
                },
                'attr' => [
                    'class' => 'select2'
                ]
            ])*/
        ;
    }

    /*public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\UserBundle\Entity\User'
        ]);
    }*/

    public function getName()
    {
        return 'sto_content_user_personal';
    }
}
