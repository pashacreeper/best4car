<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Doctrine\ORM\EntityRepository;

class CompanyAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'StoCoreBundle:Admin:edit.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('slogan')
            ->add('fullName')
            ->add('web')
            ->add('specializations')
            ->add('additionalServices')
            ->add('autoServices')
            ->add('logoName')
            ->add('workingTime')
            ->add('skype')
            ->add('email')
            ->add('address')
            ->add('gps')
            ->add('createtDate')
            ->add('photos')
            ->add('socialNetworks')
            ->add('rating')
            ->add('reviews')
            ->add('deals')
            ->add('description')
            ->add('subscribable')
            ->add('hourPrice')
            ->add('currency')
            ->add('administratorContactInfo')
            ->add('visible')
            ->add('notes')
            ->add('groups')
            ->add('gallery')
            ->add('updatedAt')
            ->add('city')
            ->add('autos')
            ->add('linkVK')
            ->add('linkTW')
            ->add('linkFB')
            ->add('companyManager')
            ->add('contacts')
            ->add('feedbacks')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Компания')
                ->add('name', null, array(
                    'required' => true
                ))
                ->add('slogan')
                ->add('fullName')
                ->add('web')
                ->add('additionalServices', null, array(
                    'required' => false,
                ))
                ->add('autoServices', null, array(
                    'required' => false,
                ))
                ->add('logo', 'file', array(
                    'required' => false,
                ))
                ->add('adminLogoDelete', 'checkbox', [
                    'label' => 'Удалить логотип?',
                    'required' => false,
                ])
                ->add('skype')
                ->add('email')
                ->add('address')
                ->add('gps')
                ->add('createtDate', 'date', [
                    'years' => range(1900, date('Y'))
                ])
                ->add('photos')
                ->add('socialNetworks')
                ->add('rating')
                ->add('reviews')
                ->add('description')
                ->add('subscribable', null, array(
                    'required' => false,
                ))
                ->add('hourPrice')
                ->add('currency')
                ->add('administratorContactInfo')
                ->add('visible', null, array(
                    'required' => false,
                ))
                ->add('notes')
                ->add('groups', null, array(
                    'required' => false,
                ))
                ->add('city')
                ->add('autos', null, array(
                    'required' => false,
                ))
                ->add('linkVK')
                ->add('linkTW')
                ->add('linkFB')
                ->add('autos', null, [
                    'required' => false,
                ])
            ->end()
            ->with('Контакты')
                ->add('contacts', 'sonata_type_collection', [
                        'by_reference' => false,
                        'required' => false
                    ], [
                        'edit' => 'inline',
                        'inline' => 'table',
                    ]
                )
            ->end()
            ->with('Галерея')
                ->add('gallery', 'sonata_type_collection', [
                        'by_reference' => false,
                        'required' => false
                    ], [
                        'edit' => 'inline',
                        'inline' => 'table',
                    ]
                )
            ->end()
            ->with('Менеджеры')
                ->add('companyManager', 'sonata_type_collection', [
                        'by_reference' => false,
                        'required' => false
                    ], [
                        'edit' => 'inline',
                        'inline' => 'table',
                    ]
                )
            ->end()
            ->with('Специализация')
                ->add('specializations', 'sonata_type_collection', [
                        'by_reference' => false,
                    ], [
                        'edit' => 'inline',
                        'inline' => 'table',
                    ]
                )
            ->end()
            ->with('Время работы')
                ->add('workingTime', 'sonata_type_collection', [
                        'by_reference' => false,
                    ], [
                        'edit' => 'inline',
                        'inline' => 'table',
                    ]
                )
            ->end()
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('logo', null, ['template' => 'StoCoreBundle:Admin:list_image.html.twig'])
            ->addIdentifier('name')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('visible')
            ->add('city')
            ->add('companyManager')
            ->add('specializations.type')
            ->add('specializations.subType', null, [], null, [
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('subType')
                        ->where('subType.parent is NOT NULL');
                }
            ])
        ;
    }

    public function preUpdate($object)
    {
        foreach ($object->getGallery() as $file) {
            $file->setUpdatedAt(new \Datetime('now'));
        }
    }

}
