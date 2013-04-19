<?php
namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;

class CompanyWorkingTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from', 'time',[
                'label_render' => false,
                'attr' => [
                    //'data-mask' => '+7 (999) 999-99-99',
                    //'placeholder' => 'Телефон',
                    //'class' => 'input-medium'
                ],
            ])
            ->add('till', 'time',
                [
                    'label_render' => false,
                    'attr' => [
                        //'placeholder' => 'Описание',
                        //'class' => 'input-small'
                    ]
                ]
            )
            ->add('description', 'text',
                [
                    'label_render' => false,
                    'required' => false,

                    'attr' => [
                        'placeholder' => 'Описание',
                        //'class' => 'input-small'
                    ]
                ]
            )
            ;
    }

    public function getName()
    {
        return 'sto_admin_company_workingtime';
    }
}
