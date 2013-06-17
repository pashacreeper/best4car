<?php

namespace Sto\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sto\CoreBundle\Entity\Dictionary;

class ChoiceCityController extends Controller
{
    public function preExecute()
    {
        $session = $this->getRequest()->getSession();
        $serializer = $this->container->get('jms_serializer');
        if ($session->has('city')) {
            $city = $serializer->deserialize($session->get('city'),'Sto\CoreBundle\Entity\Dictionary\Country','json');
            $session->set('cityName', $city->getName());
        } elseif ($this->getUser() && $this->getUser()->getCity()) {
            $city = $this->getUser()->getCity();
            $session->set('city', $serializer->serialize($city, 'json'));
            $session->set('cityName', $city->getName());
        } else {
            $city = $this->getDoctrine()->getManager()->getRepository('StoCoreBundle:Dictionary\Country')->findOneById(102);
            $session->set('city', $serializer->serialize($city, 'json'));
            $session->set('cityName', $city->getName());
        }

        return [
            'currentCity' => $city
        ];
    }
}
