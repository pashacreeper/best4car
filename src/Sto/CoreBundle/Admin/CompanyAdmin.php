<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Doctrine\ORM\EntityRepository;
use Sto\ContentBundle\Form\Extension\ChoiceList\CompanyRegistrationStep;
use Doctrine\ORM\EntityManager;

class CompanyAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    /**
     * @var EntityManager
     */
    protected $em;

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
                ->add('logo', 'file', array(
                    'required' => false,
                ))
                ->add('adminLogoDelete', 'checkbox', [
                    'label' => 'Удалить логотип?',
                    'required' => false,
                ])
                ->add('skype')
                ->add('address')
                ->add('gps')
                ->add('createtDate', 'date', [
                    'years' => range(1900, date('Y'))
                ])
                ->add('rating')
                ->add('description')
                ->add('subscribable', null, array(
                    'required' => false,
                ))
                ->add('vip', null, array(
                    'required' => false,
                ))
                ->add('hourPrice')
                ->add('currency')
                ->add('administratorContactInfo')
                ->add('visible', null, array(
                    'required' => false,
                ))
                ->add('notes')
                ->add('city')
                ->add('allAuto', 'checkbox', [
                    'label' => 'Все марки?',
                    'required' => false,
                ])
                ->add('autos', null, array(
                    'required' => false,
                ))

                ->add('registredFully')
                ->add('registrationStep', 'choice', [
                    'required' => false,
                    'choice_list' => new CompanyRegistrationStep(),
                    'empty_value' => 'Выберите один из пунктов',
                    'empty_data' => null
                ])
            ->end()
            ->with('Контакты')
                ->add('linkVK')
                ->add('linkTW')
                ->add('linkFB')
                ->add('phones', 'sonata_type_collection', [
                        'by_reference' => false,
                        'required' => false
                    ], [
                        'edit' => 'inline',
                        'inline' => 'table',
                    ]
                )
                ->add('emails', 'sonata_type_collection', [
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
            ->add('specializations.type', null, [], null, [
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('type')
                        ->where('type.parent is NULL');
                }
            ])
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
        $this->setCompanyForPhones($object);
        $this->setCompanyForEmails($object);
        foreach ($object->getGallery() as $file) {
            $file->setUpdatedAt(new \Datetime('now'));
        }
    }

    public function prePersist($object)
    {
        $this->setCompanyForPhones($object);
        $this->setCompanyForEmails($object);
    }

    private function setCompanyForPhones($object)
    {
        foreach ($object->getPhones() as $phone) {
            if (!$phone->getCompany()) {
                $phone->setCompany($object);
                $this->em->persist($phone);
            }
        }
    }

    private function setCompanyForEmails($object)
    {
        foreach ($object->getEmails() as $email) {
            if (!$email->getCompany()) {
                $email->setCompany($object);
                $this->em->persist($email);
            }
        }
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }
}
