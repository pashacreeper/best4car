<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sto\CoreBundle\Entity\Dictionary\City;

class CityController extends Controller
{
    /**
     * Ajax get cities
     *
     * @Route("/ajax/get-user-city", name="get_user_city")
     */
    public function getUserCity()
    {
        $session = $this->getRequest()->getSession();
        $city_name = ($session->get('user_city_name')) ? $session->get('user_city_name') : "Ваш город";

        $response = new Response(json_encode(['user_city' => $city_name ]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Ajax save city
     * @Route("/ajax/save-city", name="city_ajax_save")
     */
    public function saveCityAjax(Request $request)
    {
        $session = $request->getSession();

        if ($request->get('city_id'))
            $session->set('user_city_id', $request->get('city_id') );
        if ($request->get('city_name'))
            $session->set('user_city_name', $request->get('city_name') );

        $city_name = $session->get('user_city_name');

        $response = new Response(json_encode(['result' => $city_name ]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
