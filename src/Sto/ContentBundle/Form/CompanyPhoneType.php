<?php
namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompanyPhoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', 'text', [
                    'label' => false,
                    'required' => true,
                    'attr' => [
                        'placeholder' => '8 (921) 123-45-67',
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
                        'placeholder' => 'Многоканальный',
                        'class' => 'inputFormEnter span4'
                    ],
                    'constraints' => [
                        new Assert\NotBlank()
                    ]
                ]
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\CompanyPhone',
        ]);
    }

    public function getName()
    {
        return 'sto_company_phone';
    }
}
