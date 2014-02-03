<?php

namespace Sto\CoreBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use JMS\Serializer\Serializer;
use Sto\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;

class CityManager
{
    /** @var \Doctrine\Common\Persistence\ObjectManager */
    private $em;
    /** @var \Symfony\Component\HttpFoundation\Session\Session */
    private $session;
    /** @var \JMS\Serializer\Serializer */
    private $serializer;
    /** @var \Symfony\Component\Security\Core\SecurityContext */
    private $securityContext;
    /** @var string */
    private $defaultCityId;

    public function __construct(ObjectManager $em, Session $session, Serializer $serializer, SecurityContext $securityContext, $defaultCityId)
    {
        $this->em = $em;
        $this->session = $session;
        $this->serializer = $serializer;
        $this->securityContext = $securityContext;
        $this->defaultCityId = $defaultCityId;
    }

    public function selectedCity()
    {
        if ($token = $this->securityContext->getToken()) {
            $user = $token->getUser();
        }

        if ($this->session->has('city') && $this->session->get('city') !== 'null') {
            $city = $this->serializer->deserialize($this->session->get('city'), 'Sto\CoreBundle\Entity\Country', 'json');
        } else {
            $city = (isset($user) && $user instanceof User)
                ? $user->getCity()
                : $this->em
                    ->getRepository('StoCoreBundle:Country')
                    ->find($this->defaultCityId)
            ;
        }

        return $city;
    }
}
