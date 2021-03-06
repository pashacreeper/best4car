<?php

namespace Sto\ContentBundle\Form;

use Doctrine\ORM\EntityManager;
use Sto\ContentBundle\Form\DataTransformer\CompanyManagerTransformer;
use Sto\CoreBundle\Validator\Constraints\CompanyManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

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
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'Ник пользователя на сайте',
                        'class' => 'inputFormEnter span4'
                    ],
                    'constraints' => [
                        new CompanyManager(),
                    ]
                ])
                ->addModelTransformer($transformer)
            )
            ->add('phone', 'text', [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Телефон или email',
                    'class' => 'inputFormEnter span3'
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\CompanyManager',
        ]);
    }

    public function getName()
    {
        return 'sto_content_company_manager';
    }
}
