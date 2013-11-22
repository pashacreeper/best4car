<?php
namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;

class CompanyPhoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', 'text', [
                    'label' => false,
                    'attr' => [
                        'placeholder' => '8 (921) 123-45-67',
                        'class' => 'inputFormEnter span3'
                    ],
                ])
            ->add('description', 'text', [
                    'max_length' => 35,
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Многоканальный',
                        'class' => 'inputFormEnter span4'
                    ]
                ]
            );
    }

    public function getName()
    {
        return 'sto_company_phone';
    }
}
