<?php

namespace Sto\CoreBundle\Service\Controller;

use Sto\ContentBundle\Form\Type\CompaniesSortType;
use Sto\CoreBundle\Service\Base\Controller;
use Sto\CoreBundle\Service\CityManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Company
 *
 * @package Sto\CoreBundle\Service\Controller
 */
class Company extends Controller
{
    /** @var CityManager */
    private $cityManager;

    /**
     * @param CityManager $cityManager
     */
    public function setCityManager(CityManager $cityManager)
    {
        $this->cityManager = $cityManager;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function mainPage(Request $request)
    {
        $companySortForm = $this->createForm(new CompaniesSortType());

        return [
            'city'     => $this->cityManager->selectedCity(),
            'words'    => $request->query->get('search'),
            'sortForm' => $companySortForm->createView()
        ];
    }
}
