<?php
namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sto\AdminBundle\Form\DataTransformer\DayOfWeekTransformer;
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
            ->add('from', 'time',[
                'label_render' => false,
            ])
            ->add('till', 'time',[
                    'label_render' => false,
                ])
            ->add(
                $builder->create('dayFrom', 'entity', [
                    'label_render' => false,
                    'class' => 'Sto\CoreBundle\Entity\Dictionary\WeekDay',
                    'attr' => [
                        'class' => 'input-medium'
                    ],
                ])
                ->addModelTransformer($transformer)
            )
            ->add(
                $builder->create('dayTill', 'entity', [
                    'label_render' => false,
                    'class' => 'Sto\CoreBundle\Entity\Dictionary\WeekDay',
                    'attr' => [
                        'class' => 'input-medium'
                    ],
                ])
                ->addModelTransformer($transformer)
            )
            ;
    }

    public function getName()
    {
        return 'sto_admin_company_workingtime';
    }
}
