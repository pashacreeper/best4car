<?php
namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sto\ContentBundle\Form\DataTransformer\DayOfWeekTransformer;
use Doctrine\ORM\EntityManager;

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
        $transformer = new DayOfWeekTransformer($entityManager);

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
                ->addModelTransformer($transformer)
            )
            ->add('from', 'text', [
                'label' => false,
                'attr' => [
                    'class' => 'inputTime',
                    'data-format' => 'hh:mm:ss'
                ]
            ])
            ->add('till', 'text', [
                'label' => false,
                'attr' => [
                    'class' => 'inputTime',
                    'data-format' => 'hh:mm:ss'
                ]
            ])
        ;
    }

    public function getName()
    {
        return 'sto_admin_company_workingtime';
    }
}
