<?php
namespace Sto\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompanyEmailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'info@company.com',
                    'class' => 'inputFormEnter span3'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email([
                        'message' => "Необходимо указать правильный email адрес",
                        'checkMX' => true
                    ])
                ]
            ])
            ->add('description', 'text', [
                    'max_length' => 35,
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Основной email',
                        'class' => 'inputFormEnter span4'
                    ]
                ]
            )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\CompanyEmail',
        ]);
    }

    public function getName()
    {
        return 'sto_company_register_emails';
    }
}
