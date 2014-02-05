<?php
namespace Sto\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class UserEmailAdmin extends Admin
{
    protected $translationDomain = 'SonataAdmin';
    protected $baseRouteName = 'admin_vendor_bundlename_adminclassname';
    protected $baseRoutePattern = 'user_email_list';

    public function getExportFields()
    {
        return ['email'];
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('email')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('groups')
            ->add('ratingGroup')
        ;
    }
}
