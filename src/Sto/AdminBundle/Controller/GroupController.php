<?php
namespace Sto\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\UserBundle\Controller\GroupController as BaseClass;

/**
 * Group Controller
 *
 * @Route("/group")
 */
class GroupController extends BaseClass
{
    /**
     * Lists all User entities.
     *
     * @Route("/list", name="group_list")
     *
     */
    public function listAction()
    {
        return parent::listAction();
    }
}
