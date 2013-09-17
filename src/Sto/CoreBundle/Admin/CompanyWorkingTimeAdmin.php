<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class CompanyWorkingTimeAdmin extends Admin
{
    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('days', 'sonata_type_immutable_array', [
                'keys' => [
                    [0, 'checkbox', ['required' => false, 'label' => 'Понедельник']],
                    [1, 'checkbox', ['required' => false, 'label' => 'Вторник']],
                    [2, 'checkbox', ['required' => false, 'label' => 'Среда']],
                    [3, 'checkbox', ['required' => false, 'label' => 'Четверг']],
                    [4, 'checkbox', ['required' => false, 'label' => 'Пятница']],
                    [5, 'checkbox', ['required' => false, 'label' => 'Суббота']],
                    [6, 'checkbox', ['required' => false, 'label' => 'Воскресенье']]
                ]
            ])
            ->add('fromTime')
            ->add('tillTime')
        ;
    }
}
