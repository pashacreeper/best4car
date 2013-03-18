<?php

namespace Sto\AdminBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Name'
            ])
            ->add('slogan', null, [
                'label' => 'Slogan',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('fullName', null, [
                'label' => 'Full name',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('web', 'url', [
                'label' => 'Home page',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('specialization', null, [
                'label' => 'Specialization',
            ])
            ->add('services', null, [
                'label' => 'Services',
            ])
            ->add('additionalServices', null, [
                'label' => 'Additional services',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('logo', null, [
                'label' => 'Logo',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('workingTime', null, [
                'label' => 'Working time',
            ])
            ->add('phones', null, [
                'label' => 'Phones',
                'attr' => [
                    'data-mask' => '+7 (999) 999-99-99'
                ]
            ])
            ->add('skype', null, [
                'label' => 'Skype',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('email', 'email', [
                'label' => 'E-mail',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('address', null, [
                'label' => 'Address',
            ])
            ->add('gps', null, [
                'label' => 'GPS',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('createtDate', 'date', [
                'widget' => 'single_text',
                'datepicker' => true,
                'label' => 'Createt date',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('photos', null, [
                'label' => 'Photos',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('socialNetworks', null, [
                'label' => 'Social networks',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('rating', null, [
                'label' => 'Rating',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('reviews', null, [
                'label' => 'Reviews',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('description', 'textarea', [
                'label' => 'Description',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('subscribable', null, [
                'label' => 'Subscribable',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('hourPrice', null, [
                'label' => 'Hour price',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('managers', null, [
                'label' => 'Managers',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('administratorContactInfo', null, [
                'label' => 'Administrator contact',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('visible', null, [
                'label' => 'Visible',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('notes', 'textarea', [
                'label' => 'Notes',
                'required' => false,
                'render_optional_text' => false
            ])
            ->add('groups')
            ->add('ratingGroup')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\Company'
        ]);
    }

    public function getName()
    {
        return 'sto_admin_company';
    }
}
