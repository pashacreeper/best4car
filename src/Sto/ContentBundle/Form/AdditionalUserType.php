<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdditionalUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, [
                'label' => 'Email'
            ])
            ->add('password', 'password', [
                'label' => 'Пароль'
            ])
            ->add('birthDate', 'date', [
                'label' => 'Дата рождения',
                'years' => range (1920, date('Y')),
            ])
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
        return 'sto_content_user_additional';
    }
}
