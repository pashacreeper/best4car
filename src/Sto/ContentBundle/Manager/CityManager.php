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


        if ($session->has('city')) {
            $city = $serializer->deserialize($session->get('city'), 'Sto\CoreBundle\Entity\Dictionary\Country','json');
        } else {
            $city = ($user instanceof User)? $user->getCity() : $this->em->getRepository('StoCoreBundle:Dictionary\Country')->findOneById(102);
        }

        return $city;
    }
}
