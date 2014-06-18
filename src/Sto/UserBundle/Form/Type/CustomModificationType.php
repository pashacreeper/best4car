<?php

namespace Sto\UserBundle\Form\Type;

use Sto\ContentBundle\Form\Extension\ChoiceList\BodyType;
use Sto\ContentBundle\Form\Extension\ChoiceList\EngineType;
use Sto\ContentBundle\Form\Extension\ChoiceList\FuelType;
use Sto\ContentBundle\Form\Extension\ChoiceList\WheelType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CustomModificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('engineType', 'choice', [
                'label'       => 'Тип двигателя',
                'empty_value' => 'Выберите тип двигателя',
                'required'    => false,
                'choice_list' => new EngineType(),
                'attr'        => [
                    'class' => 'styled'
                ]
            ])
            ->add('engineModel', null, [
                'label'    => 'Модель двигателя',
                'required' => false,
                'attr'     => [
                    'class' => 'inputField'
                ]
            ])
            ->add('engineVolume', 'text', [
                'label'    => 'Объем',
                'required' => false,
                'attr'     => [
                    'class' => 'smallInputField'
                ]
            ])
            ->add('enginePower', 'text', [
                'label'    => 'Мощность',
                'required' => false,
                'attr'     => [
                    'class' => 'smallInputField'
                ]
            ])
            ->add('fuelTypes', 'choice', [
                'label'       => 'Топливо',
                'required'    => false,
                'multiple'    => true,
                'expanded'    => true,
                'choice_list' => new FuelType(),
                'attr'        => [
                    'class' => 'priceLevelMarker inline'
                ]
            ])
            ->add('wheelType', 'choice', [
                'label'       => 'Привод',
                'required'    => false,
                'empty_value' => 'Выберите тип привода',
                'choice_list' => new WheelType(),
                'attr'        => [
                    'class' => 'styled inputField'
                ]
            ])
            ->add('bodyType', 'choice', [
                'label'       => 'Тип кузова',
                'required'    => false,
                'empty_value' => 'Выберите тип кузова',
                'choice_list' => new BodyType(),
                'attr'        => [
                    'class' => 'styled inputField'
                ]
            ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'car_custom_modification';
    }
}
