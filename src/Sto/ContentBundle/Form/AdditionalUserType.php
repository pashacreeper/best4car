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
                    'label' => 'Email',
                    'attr' => [
                        'class' => 'inputField span12'
                    ]
                ])
            ->add('password', 'password', [
                    'label' => 'Пароль',
                    'attr' => [
                        'class' => 'inputField span12'
                    ]
                ])
            ->add('birthDate', 'date', [
                    'label' => 'Дата рождения',
                    'years' => range (1920, date('Y')),
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'required' => false,
                    'attr' => [
                        'class' => "inputData span4",
                        'data-format' => "yyyy-MM-dd"
                    ]
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
