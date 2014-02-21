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
use Sto\CoreBundle\Service\CityManager;
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
    private $emMock;
    private $formFactoryMock;
    private $requestMock;
    private $cityManagerMock;

    public function tearDown()
    {
        m::close();
    }

    public function test_service_main_page()
    {
        $this->setMocks();

        $this->cityManagerMock
            ->shouldReceive('selectedCity')
            ->once()
            ->andReturn(['city' => 102])
            ->mock();

        $this->requestMock->query
            ->shouldReceive('get')
            ->with('search')
            ->once()
            ->andReturn(null)
            ->mock();

        $this->formFactoryMock
            ->shouldReceive('create')
            ->once()
            ->andReturnUsing(function () {
                $m = m::mock('Symfony\Component\Form\FormInterface');

                return $m->shouldReceive('createView')
                    ->andReturn(['view' => 'view'])
                    ->once()
                    ->mock();
            })
            ->mock();

        $companyService = new Company($this->emMock, $this->formFactoryMock);
        $companyService->setCityManager($this->cityManagerMock);

        $this->assertEquals(
            [
                'city' => [
                    'city' => 102
                ],
                'words' => null,
                'sortForm' => [
                    'view' => 'view'
                ]
            ],
            $companyService->mainPage($this->requestMock)
        );

    }

    protected function setMocks()
    {
        $this->emMock = m::mock('Doctrine\ORM\EntityManager');
        $this->formFactoryMock = m::mock('Symfony\Component\Form\FormFactory');
        $this->requestMock = m::mock('Symfony\Component\HttpFoundation\Request');
        $this->requestMock->query = m::mock('Symfony\Component\HttpFoundation\ParameterBag');
        $this->cityManagerMock = m::mock('Sto\CoreBundle\Service\CityManager');
    }
}
