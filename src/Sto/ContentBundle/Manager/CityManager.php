<?php

namespace Sto\ContentBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sto\UserBundle\Entity\User;

class CityManager
{
    protected $em;

    protected $container;

    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function selectedCity()
    {
        $session = $this->container->get('session');
        $serializer = $this->container->get('jms_serializer');
        $user = null;
        $token = $this->container->get('security.context')->getToken();
        if (null !== $token) {
            $user = $token->getUser();
        }

        if ($session->has('city') && $session->get('city') !== 'null') {
            $city = $serializer->deserialize($session->get('city'), 'Sto\CoreBundle\Entity\Country', 'json');
        } else {
            $city = ($user instanceof User)
                ? $user->getCity()
                : $this->em
                    ->getRepository('StoCoreBundle:Country')
                    ->findOneById($this->container->getParameter('default_city_id'))
            ;
        }

        return $city;
    }
}
