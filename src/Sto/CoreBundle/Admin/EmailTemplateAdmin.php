<?php

namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sto\CoreBundle\Form\ChoiceList\EmailTemplateType;

class EmailTemplateAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('title')
            ->add('content')
            ->add('type')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title')
            ->add('content')
            ->add('type')
            ->add('updatedtAt')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $helpMessage = <<<HELP
<table class="table table-condensed">
    <thead>
        <tr>
            <td>Название переменной</td>
            <td>Что делает</td>
        </tr>
    </thead>
    <tbody>
        <tr class="info">
            <td colspan="2">Доступно во всех шаблонах</td>
        </tr>
        <tr>
            <td><strong>%user%</strong></td><td>Имя пользователя</td>
        </tr>
        <tr>
            <td><strong>%nick%</strong></td><td>Логин пользователя</td>
        </tr>
        <tr class="info">
            <td colspan="2">Восстановление забытого пароля</td>
        </tr>
        <tr>
            <td><strong>%link%</strong></td><td>Ссылка для восстановления пароля</td>
        </tr>
        <tr class="info">
            <td colspan="2">Ответ на отзыв</td>
        </tr>
        <tr>
            <td><strong>%link%</strong></td><td>Ссылка на страницу с отзывами, на которой был оставлен ответ на отзыв</td>
        </tr>
        <tr>
            <td><strong>%company%</strong></td><td>Название компании, если ответ относиться к компании</td>
        </tr>
        <tr>
            <td><strong>%deal%</strong></td><td>Название акции, если ответ относиться к акции</td>
        </tr>
        <tr class="info">
            <td colspan="2">Доступно во всех шаблонах для компаний</td>
        </tr>
        <tr>
            <td><strong>%company%</strong></td><td>Название компании</td>
        </tr>
        <tr>
            <td><strong>%link%</strong></td><td>Ссылка на страницу компании</td>
        </tr>
        <tr class="info">
            <td colspan="2">Новый отзыв об акции</td>
        </tr>
        <tr>
            <td><strong>%deal%</strong></td><td>Название предложения</td>
        </tr>
        <tr>
            <td><strong>%link%</strong></td><td>ссылка на страницу предложения в раздел с отзывами</td>
        </tr>
    </tbody>
</table>
HELP;

        $formMapper
            ->add('title')
            ->add('type', 'choice', [
                'choice_list' => new EmailTemplateType()
            ])
            ->add('content', null, [
                'help' => $helpMessage
            ])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('title')
            ->add('content')
            ->add('type')
            ->add('createdAt')
            ->add('updatedtAt')
        ;
    }
}
