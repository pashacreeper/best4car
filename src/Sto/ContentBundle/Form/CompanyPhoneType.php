<?php
namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyPhoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', 'text', [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Телефон',
                        'class' => 'inputFormEnter span3'
                    ],
                    'constraints' => [
                        new Assert\NotBlank()
                    ]
                ])
            ->add('description', 'text', [
                    'max_length' => 35,
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Описание',
                        'class' => 'inputFormEnter span4'
                    ],
                    'constraints' => [
                        new Assert\NotBlank()
                    ]
                ]
            );
    }

    public function getName()
    {
        return 'sto_company_phone';
    }
}
