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
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'required' => false,
                    'attr' => [
                        'class' => "inputData span4 init-ui-datepicker-birthdate no-margin",
                        'data-format' => "dd-MM-yyyy"
                    ]
                ])
        ;
    }

    public function getName()
    {
        return 'sto_content_user_additional';
    }
}
