<?php

namespace Sto\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Sto\ContentBundle\Form\DataTransformer\TimestampToDateTransformer;
use Sto\ContentBundle\Form\DataTransformer\TimeToDateTransformer;
use Symfony\Component\Validator\Constraints as Assert;
use Sto\ContentBundle\Form\Validator\DealAutoMarks;

class DealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new TimestampToDateTransformer();
        $timeTransformer = new TimeToDateTransformer();
        $builder
            ->add('name', 'text', [
                'label' => 'Название акции',
                'required' => true,
                'attr' => [
                    "class" => "inputField span12",
                ]
            ])
            ->add('description', 'textarea', [
                'label' => 'Описание акции',
                'required' => true,
                'attr' => [
                    "rows" => 4,
                    "class" => "span12 description-textarea",
                    'data-length' => '1250',
                ]
            ])
            ->add('autoServices', null, [
                'label' => 'Узлы и работы',
                'required' => false,
                'multiple' => true,
                'class' => 'StoCoreBundle:autoServices',
                'query_builder' => function (EntityRepository $er) { return $er->createQueryBuilder('ct')->select('ct'); },
                'attr' => [
                    'class' => 'chosen-multiple',
                    'data-placeholder' => "Выберите варианты"
                ]
            ])
            ->add('auto', null, [
                'label' => 'Автомобили',
                'required' => false,
                'multiple' => true,
                'class' => 'StoCoreBundle:Mark',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('mark')
                        ->where('mark.visible = true')
                        ;
                },
                'constraints' => [
                    new DealAutoMarks()
                ],
                'attr' => [
                    'class' => 'chzn-select-autos',
                    'data-placeholder' => "Выберите варианты"
                ]
            ])
            ->add('image', 'file', [
                'label' => 'Image',
                'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                'property_path' => 'image',
                'required' => false,
                'attr' => [
                    'data-image' => 'image1'
                ]
            ])
            ->add('image2', 'file', [
                'label' => 'Image2',
                'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                'property_path' => 'image2',
                'required' => false,
                'attr' => [
                    'data-image' => 'image2'
                ]
            ])
            ->add('image3', 'file', [
                'label' => 'Image3',
                'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                'property_path' => 'image3',
                'required' => false,
                'attr' => [
                    'data-image' => 'image3'
                ]
            ])
            ->add('terms', 'textarea', [
                'label' => 'Условия участия',
                'required' => false,
                'attr' => [
                    "rows" => 4,
                    "class" => "span12",
                ]
            ])
            ->add(
                $builder->create('startDate', 'text', [
                    'label' => 'Начало',
                    'required' => true,
                    'attr' => [
                        'class' => 'inputTime init-ui-datepicker',
                        'data-format' => 'yyyy-MM-dd'
                    ]
                ])->addModelTransformer($transformer)
            )
            ->add(
                $builder->create('endDate', 'text', [
                    'label' => 'Начало',
                    'required' => true,
                    'attr' => [
                        'class' => 'inputTime init-ui-datepicker',
                        'data-format' => 'yyyy-MM-dd'
                    ]
                ])->addModelTransformer($transformer)
            )
            ->add(
                $builder->create('startTime', 'text', [
                    'label' => 'Start time',
                    'required' => false,
                    'attr' => [
                        'class' => 'inputTime init-ui-time',
                        'data-button-index' => 'timepicker1'
                    ]
                ])->addModelTransformer($timeTransformer)
            )
            ->add(
                $builder->create('endTime', 'text', [
                    'label' => 'End time',
                    'required' => false,
                    'attr' => [
                        'class' => 'inputTime init-ui-time',
                        'data-button-index' => 'timepicker2'
                    ]
                ])->addModelTransformer($timeTransformer)
            )
            ->add('place', null, [
                'label' => 'Место проведе',
                'required' => false,
                'attr' => [
                    'class' => "span12"
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
            ->add('onCompanyPlace', 'checkbox', [
                'label' => 'По месту размещения компании',
                'required' => false,
            ])
            ->add('contactInformationName', 'text', [
                'label' => 'Контактное лицо',
                'required' => false,
                'attr' => [
                    "class" => "inputField span12",
                ]
            ])
            ->add('contactInformationPhone', 'text', [
                'label' => 'Контактный номер',
                'required' => false,
                'attr' => [
                    "class" => "inputField span12",
                ]
            ])
            ->add('type', null, [
                'label' => 'Тип акции',
                'required' => true,
                'attr' => [
                    'class' => 'styled'
                ]
            ])
            ->add('gps', 'hidden', [])
            ->add('companyId', 'hidden', [])
        ;

        if ($options['manyPlaces'] && $options['user'] && $options['company']) {
            $user = $options['user'];
            $company = $options['company'];
            $builder->add('additionalCompanies', null, [
                'expanded' => true,
                'property' => 'nameWithAddress',
                'query_builder' => function (EntityRepository $er) use ($user, $company) {
                    return $er->createQueryBuilder('ac')
                        ->join('ac.companyManager', 'cm')
                        ->where('cm.user = :user')
                        ->andWhere('ac <> :company')
                        ->andWhere('ac.registredFully = true')
                        ->setParameter('user', $user)
                        ->setParameter('company', $company)
                    ;
                },
            ]);
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Sto\CoreBundle\Entity\Deal',
            'manyPlaces' => false,
            'user' => null,
            'company' => null,
        ]);
    }

    public function getName()
    {
        return 'sto_content_deal';
    }
}
