<?php
namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sto\ContentBundle\Form\DataTransformer\TimeToDateTransformer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompanyWorkingTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $timeTransformer = new TimeToDateTransformer();

        $builder
            ->add('days', 'sonata_type_immutable_array', [
                'keys' => [
                    [0, 'checkbox', ['required' => false, 'label' => 'Пн']],
                    [1, 'checkbox', ['required' => false, 'label' => 'Вт']],
                    [2, 'checkbox', ['required' => false, 'label' => 'Ср']],
                    [3, 'checkbox', ['required' => false, 'label' => 'Чт']],
                    [4, 'checkbox', ['required' => false, 'label' => 'Пт']],
                    [5, 'checkbox', ['required' => false, 'label' => 'Сб']],
                    [6, 'checkbox', ['required' => false, 'label' => 'Вс']]
                ],
                'label' => false,
                'attr' => [
                    'class' => 'workingTimeDays'
                ]
            ])
            ->add(
                $builder->create(
                    'fromTime', 'text', [
                        'label' => false,
                        'attr' => [
                            'class' => 'inputTime',
                            'data-format' => 'hh:mm:ss'
                        ]
                    ]
                )
                ->addModelTransformer($timeTransformer)
            )
            ->add(
                $builder->create(
                    'tillTime', 'text', [
                        'label' => false,
                        'attr' => [
                            'class' => 'inputTime',
                            'data-format' => 'hh:mm:ss'
                        ]
                    ]
                )
                ->addModelTransformer($timeTransformer)
            )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\CompanyWorkingTime'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_company_workingtime';
    }
}
