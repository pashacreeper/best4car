<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 03.02.14
 * Time: 16:02
 */

namespace Sto\CoreBundle\Tests\Service\Controller;

use Doctrine\ORM\EntityManager;
use \Mockery as m;
use Sto\CoreBundle\Service\Controller\Company;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CompanyTest
 *
 * @package Sto\CoreBundle\Tests\Service\Controller
 */
class CompanyTest extends \PHPUnit_Framework_TestCase
{
    /** @var EntityManager */
    private $emMock;
    /** @var FormFactory */
    private $formFactoryMock;
    /** @var Request */
    private $requestMock;

    public function tearDown()
    {
        m::close();
    }

    public function test_service_main_page()
    {
        $this->setMocks();

        $companyService = new Company($this->emMock, $this->formFactoryMock);
        $companyService->mainPage($this->requestMock);
    }

    protected function setMocks()
    {
        $this->emMock = m::mock('Doctrine\ORM\EntityManager');
        $this->formFactoryMock = m::mock('Symfony\Component\Form\FormFactory');
        $this->requestMock = m::mock('Symfony\Component\HttpFoundation\Request');
    }
}
