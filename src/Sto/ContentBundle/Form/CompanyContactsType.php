<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Doctrine\ORM\EntityRepository;

class CompanyContactsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'entity', [
                'label' => ' ',
                'class' => 'StoCoreBundle:Dictionary\ContactType',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.name')
                    ;
                },
            ])
            ->add('value', 'text', [
                'label' => ' ',
                'attr' => [
                    'class' => 'input-medium'
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\UserBundle\Entity\Contacts'
        ]);
    }

    public function getName()
    {
        return 'sto_content_company_contacts';
    }
}
