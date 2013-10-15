<?php
namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class AdvancedSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('companyType', 'entity', [
                'class' => 'StoCoreBundle:CompanyType',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->where('ct.parent is null')
                    ;
                },
                'required' => false,
                'empty_value' => 'Все',
                'attr' => [
                    'class' => 'chzn-select',
                ]
            ])
            ->add('subCompanyType', 'entity', [
                'class' => 'StoCoreBundle:CompanyType',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->where('ct.parent is not null')
                    ;
                },
                'required' => false,
                'empty_value' => 'Все',
                'attr' => [
                    'class' => 'chzn-select',
                ]
            ])
            ->add('auto', 'entity', [
                'class' => 'StoCoreBundle:Mark',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('mark')
                        ->where('mark.visible = true')
                    ;
                },
                'required' => false,
                'empty_value' => 'Все',
                'attr' => [
                    'class' => 'chzn-select',
                ]
            ])
        ;
    }

    public function getName()
    {
        return 'sto_content_advanced_search';
    }
}
