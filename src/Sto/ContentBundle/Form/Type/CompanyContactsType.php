<?php
namespace Sto\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sto\ContentBundle\Form\CompanyPhoneType;
use Sto\ContentBundle\Form\CompanyWorkingTimeType;
use Sto\ContentBundle\Form\CompanyManagerType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompanyContactsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', null, [
                'label' => 'Адрес',
                'attr' => [
                    'class' => 'inputFormEnter inputleftContDate'
                ]
            ])
            ->add('gps', null, [
                'label' => 'Координаты на карте',
                'required' => true,
                'attr' => [
                    // 'style' => 'display:none'
                ]
            ])
            ->add('phones','collection', array(
                'label' => ' ',
                'type' => new CompanyPhoneType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ))
            ->add('workingTime','collection', array(
                'label' => ' ',
                'type' => new CompanyWorkingTimeType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
                'by_reference' => false
            ))
            ->add('companyManager', 'collection', array(
                'label' => ' ',
                'type' => new CompanyManagerType($options['em']),
                'by_reference' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
            ))
            ->add('web', 'text', [
                'label' => 'Адрес сайта',
                'required' => false,
            ])
            ->add('skype', null, [
                'label' => 'Skype',
                'required' => false,
            ])
            ->add('email', 'email', [
                'label' => 'E-mail',
                'required' => false,
            ])
            ->add('linkVK', 'text', [
                'label' => 'Группа Vkontakte',
                'required' => false,
                'attr' => [
                    'class' => 'inputFormEnter iputVkC'
                ]
            ])
            ->add('linkFB', 'text', [
                'label' => 'Страница Facebook',
                'required' => false,
                'attr' => [
                    'class' => 'inputFormEnter iputFaceC'
                ]
            ])
            ->add('linkTW', 'text', [
                'label' => 'Twitter',
                'required' => false,
                'attr' => [
                    'class' => 'inputFormEnter iputTwittC'
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired([
                'em',
            ])
            ->setAllowedTypes([
                'em' => 'Doctrine\Common\Persistence\ObjectManager',
            ])
        ;
    }

    public function getName()
    {
        return 'sto_company_register_contacts';
    }
}
