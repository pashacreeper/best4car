<?php
namespace Sto\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

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
                ]
            ])
            ->add('name', 'text', [
                'label' => 'Краткое наименование',
                'attr' => [
                    'class' => 'input-xxlarge'
                ]
            ])
            ->add('slogan', 'text', [
                'label' => 'Девиз (слоган)',
                'required' => false,
                'attr' => [
                    'class' => 'input-xxlarge'
                ]
            ])
            ->add('createtDate', 'datetime', [
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'label' => 'Начало работы на рынке',
                'required' => false,
                'attr' => [
                    'class' => "inputData init-ui-datepicker",
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
                ]
            ])
        ;
    }

    public function getName()
    {
        return 'sto_company_reguister_base';
    }
}
