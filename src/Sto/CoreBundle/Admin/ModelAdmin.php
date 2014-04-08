<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;

class ModelAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';

    public function buildBreadcrumbs($action, MenuItemInterface $menu = null)
    {
        if (isset($this->breadcrumbs[$action])) {
            return $this->breadcrumbs[$action];
        }

        if (!$menu) {
            $menu = $this->menuFactory->createItem('root');

            $menu = $menu->addChild(
                $this->trans($this->getLabelTranslatorStrategy()->getLabel('dashboard', 'breadcrumb', 'link'), array(), 'SonataAdminBundle'),
                array('uri' => $this->routeGenerator->generate('sonata_admin_dashboard'))
            );
        }


        $filters = $this->getFilterParameters();
        if(isset($filters['parent']) && isset($filters['parent']['value'])) {
            $mark = $this->getModelManager()->find('StoCoreBundle:Mark', $filters['parent']['value']);
            $menu = $menu->addChild($mark->getName());
        }

        $menu = $menu->addChild(
            $this->trans($this->getLabelTranslatorStrategy()->getLabel(sprintf('%s_list', $this->getClassnameLabel()), 'breadcrumb', 'link')),
            array('uri' => $this->hasRoute('list') && $this->isGranted('LIST') ? $this->generateUrl('list') : null)
        );

        $childAdmin = $this->getCurrentChildAdmin();

        if ($childAdmin) {
            $id = $this->request->get($this->getIdParameter());

            $menu = $menu->addChild(
                $this->toString($this->getSubject()),
                array('uri' => $this->hasRoute('edit') && $this->isGranted('EDIT') ? $this->generateUrl('edit', array('id' => $id)) : null)
            );

            return $childAdmin->buildBreadcrumbs($action, $menu);

        } elseif ($this->isChild()) {

            if ($action == 'list') {
                $menu->setUri(false);
            } elseif ($action != 'create' && $this->hasSubject()) {
                $menu = $menu->addChild($this->toString($this->getSubject()));
            } else {
                $menu = $menu->addChild(
                    $this->trans($this->getLabelTranslatorStrategy()->getLabel(sprintf('%s_%s', $this->getClassnameLabel(), $action), 'breadcrumb', 'link'))
                );
            }

        } elseif ($action != 'list' && $this->hasSubject()) {
            $menu = $menu->addChild($this->toString($this->getSubject()));
        } elseif ($action != 'list') {
            $menu = $menu->addChild(
                $this->trans($this->getLabelTranslatorStrategy()->getLabel(sprintf('%s_%s', $this->getClassnameLabel(), $action), 'breadcrumb', 'link'))
            );
        } else {
            $menu->getBreadcrumbsArray();
        }

        return $this->breadcrumbs[$action] = $menu;
    }

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('children')
            ->add('parent')
            ->add('uri')
            ->add('visible')
        ;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, [
                'required' => true
            ])
            ->add('parent', null, [
                'required' => true
            ])
            ->add('uri')
            ->add('visible', null, [
                'required' => false
            ])
        ;
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('name')
            ->add('parent')
            ->add('uri', null, ['template' => 'StoContentBundle:Admin:url_field.html.twig'])
            ->add('visible')
            ->add('children', null, ['template' => 'StoContentBundle:Admin:model_options_list.html.twig', 'label' => 'Комплектации'])
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('parent')
            ->add('uri')
            ->add('visible')
        ;
    }
}
