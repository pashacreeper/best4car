<?php
namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;

class CompanyPhoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', 'text',[
                'label_render' => false,
                'attr' => [
                    'data-mask' => '+7 (999) 999-99-99',
                    'placeholder' => 'Телефон',
                    'class' => 'input-medium'
                ],
            ])
            ->add('description', 'text',
                [
                    'max_length' => 10,
                    'required' => false,
                    'label_render' => false,
                    'attr' => [
                        'placeholder' => 'Описание',
                        'class' => 'input-small'
                    ]
                ]
            );
    }

    public function getName()
    {
        return 'sto_admin_company_phone';
    }
}
