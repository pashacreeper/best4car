<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sto\ContentBundle\Form\DataTransformer\CompanyManagerTransformer;
use Doctrine\ORM\EntityManager;

class CompanyManagerType extends AbstractType
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $entityManager = $this->em;
        $transformer = new CompanyManagerTransformer($entityManager);
        $builder
            ->add(
                $builder->create('user', 'text', [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Имя пользователя на сайте',
                        'class' => 'inputFormEnter span4'
                    ]
                ])
                ->addModelTransformer($transformer)
            )
            ->add('phone', 'text', [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Телефон',
                    'class' => 'inputFormEnter span3'
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\CompanyManager'
        ]);
    }

    public function getName()
    {
        return 'sto_content_company_manager';
    }
}
