<?php
namespace Sto\ContentBundle\Form;

use Doctrine\ORM\EntityManager;
use Sto\ContentBundle\Form\DataTransformer\DayOfWeekTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sto\ContentBundle\Form\DataTransformer\TimeToDateTransformer;

class CompanyWorkingTimeType extends AbstractType
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityManager = $this->em;
        $daysTransformer = new DayOfWeekTransformer($entityManager);
        $timeTransformer = new TimeToDateTransformer();

        $builder
            ->add(
                $builder->create('days', 'entity', [
                    'label' => false,
                    'property' => 'shortName',
                    'class' => 'Sto\CoreBundle\Entity\Dictionary\WeekDay',
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false,
                    'attr' => [
                        'class' => 'workingTimeDays'
                    ],
                ])
                ->addModelTransformer($daysTransformer)
            )
            ->add(
                $builder->create(
                    'from', 'text', [
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
                    'till', 'text', [
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

    public function getName()
    {
        return 'sto_admin_company_workingtime';
    }
}
