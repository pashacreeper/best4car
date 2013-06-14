<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CompanyManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', 'entity', [
                'label' => ' ',
                'class' => 'StoUserBundle:User',
                'query_builder' => function(EntityRepository $er) {
                         return $er->createQueryBuilder('u')
                            ->innerJoin('u.groups', 'g', 'WITH', "g.name = 'Менеджеры'")
                       ;
                    ;
                },
                'attr' => [
                    'placeholder' => 'Менеджер',
                ]
            ])
            ->add('phone', 'text', [
                'label' => ' ',
                'attr' => [
                    'data-mask' => '999-99-99?-999',
                    'placeholder' => 'Телефон',
                    'class' => 'input-medium'
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
