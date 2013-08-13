<?php
namespace Sto\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sto\ContentBundle\Form\Extension\ChoiceList\CompaniesSort;

class CompaniesSortType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sort', 'choice', [
                'choice_list' => new CompaniesSort(),
                'attr' => [
                    'class' => 'styled1',
                ]
            ]);
        ;
    }

    public function getName()
    {
        return 'sto_content_company_sort';
    }
}
