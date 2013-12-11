<?php
namespace Sto\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyBaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', 'text', [
                'label' => 'Полное наименование',
                'required' => true,
                'attr' => [
                    'class' => 'input-xxlarge'
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('name', 'text', [
                'label' => 'Краткое наименование',
                'required' => true,
                'attr' => [
                    'class' => 'input-xxlarge'
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('slogan', 'text', [
                'label' => 'Девиз (слоган)',
                'required' => false,
                'attr' => [
                    'class' => 'input-xxlarge description-textarea',
                    'data-length' => '85',
                ],
                'constraints' => [
                    new Assert\Length(['max' => 85])
                ]
            ])
            ->add('createtDate', 'datetime', [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'Начало работы на рынке',
                'required' => false,
                'attr' => [
                    'class' => "inputData init-ui-datepicker",
                ],
                'constraints' => [
                    new Assert\Date()
                ]
            ])
            ->add('city', 'entity', [
                'label' => 'Город',
                'class' => 'StoCoreBundle:Country',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('country')
                        ->where('country.parent is not null')
                    ;
                },
                'attr' => [
                    'class' => 'chzn-select input-large'
                ]
            ])
            ->add('logo', null, [
                'label' => 'Логотип компании',
                'required' => false,
                'attr' => [
                    'data-image' => 'logo',
                    'class' => 'hideLogoInput',
                ],
                'constraints' => [
                    new Assert\Image()
                ]
            ])
            ->add('adminLogoDelete', 'radio', [
                'label' => ' ',
                'required' => false,
                'attr' => [
                    'class' => 'hidden',
                ]
            ])
        ;
    }

    public function getName()
    {
        return 'sto_company_register_base';
    }
}
