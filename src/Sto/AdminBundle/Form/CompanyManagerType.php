<?php
namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CompanyManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('managers', 'entity', [
                'label' => 'Managers',
                'multiple' => true,
                'class' => 'StoUserBundle:User',
                /*'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->leftJoin('u.groups', 'g')
                        ->where('g.roles IN (?1)')
                        ->setParameter(1, 'ROLE_MANAGER')
                    ;
                },*/
                'attr' => [
                    'class' => 'select2'
                ]
            ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\Company'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_company_manager';
    }


}
