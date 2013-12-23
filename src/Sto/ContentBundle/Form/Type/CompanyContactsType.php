<?php
namespace Sto\ContentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sto\ContentBundle\Form\CompanyPhoneType;
use Sto\ContentBundle\Form\CompanyWorkingTimeType;
use Sto\ContentBundle\Form\CompanyManagerType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Sto\CoreBundle\Validator\Constraints\ConstraintWorkingTime;
use Sto\CoreBundle\Validator\Constraints\ConstraintPhones;
use Sto\CoreBundle\Validator\Constraints\CompanyManager;

class CompanyContactsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', null, [
                'label' => 'Адрес',
                'required' => true,
                'attr' => [
                    'class' => 'inputFormEnter inputleftContDate'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('gps', null, [
                'label' => 'Координаты на карте',
                'required' => false,
                'attr' => [
                    'style' => 'display:none'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('phones','collection', [
                'label' => ' ',
                'type' => new CompanyPhoneType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'error_bubbling' => false,
                'constraints' => [
                    new ConstraintPhones()
                ]
            ])
            ->add('emails', 'collection', [
                'label' => ' ',
                'type' => new CompanyEmailsType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'error_bubbling' => false,
            ])
            ->add('workingTime', 'collection', [
                'label' => ' ',
                'type' => new CompanyWorkingTimeType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'error_bubbling' => false,
                'constraints' => [
                    new ConstraintWorkingTime()
                ]
            ])
            ->add('companyManager', 'collection', [
                'label' => ' ',
                'type' => new CompanyManagerType($options['em']),
                'by_reference' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'error_bubbling' => false,
            ])
            ->add('web', 'text', [
                'label' => 'Адрес сайта',
                'required' => false,
            ])
            ->add('skype', null, [
                'label' => 'Skype',
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
