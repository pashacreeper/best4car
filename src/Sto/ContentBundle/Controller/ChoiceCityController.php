<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ChoiceCityController extends Controller
{
    public function getRefererRoute()
    {
        $request = $this->getRequest();
        $refererRoute = null;
        if ($referer = $request->headers->get('referer')) {
            $urlParts = parse_url($referer);
            try {
                if ($routeParams = $this->get('router')->match($urlParts['path'])) {
                    $refererRoute = $routeParams['_route'];
                }
            } catch (MethodNotAllowedException $e) {} catch(ResourceNotFoundException $e) {}
        }

        return $refererRoute;
    }

    public function preExecute()
    {
        $session = $this->getRequest()->getSession();
        $route = $this->getRequest()->get('_route');
        if ($route != 'user-vk-accounting'  && $route != 'user_vk_account_save') {
            $session->set('last_route', $route);
        }
        $serializer = $this->container->get('jms_serializer');
        if ($session->has('city')) {
            $city = $serializer->deserialize($session->get('city'),'Sto\CoreBundle\Entity\Country','json');
            $session->set('cityName', $city->getName());
        } elseif ($this->getUser() && ($city = $this->getUser()->getCity())) {
            $session->set('city', $serializer->serialize($city, 'json'));
            $session->set('cityName', $city->getName());
        } else {
            $defaultCityId = $this->container->getParameter('default_city_id');
            $city = $this->getDoctrine()->getManager()
                ->getRepository('StoCoreBundle:Country')
                ->findOneById($defaultCityId)
            ;
            $session->set('city', $serializer->serialize($city, 'json'));
            $session->set('cityName', $city->getName());
        }

        return [
            'currentCity' => $city
        ];
    }

    /**
     * @Template()
     */
    public function renderChosenCityAction()
    {
        return ['city' => $this->get('sto_content.manager.city')->selectedCity()];
    }
}
