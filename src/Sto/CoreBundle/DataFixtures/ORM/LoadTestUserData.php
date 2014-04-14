<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\UserBundle\Entity\User;

class LoadTestUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $user = new User;
        $user->setUsername('manager')
            ->setEmail("manager@sto.com")
            ->setFirstName('first_name')
            ->setLastName('last_name')
            ->setPhoneNumber('+7 (981) 803-86-24')
            ->setLinkGarage('#')
            ->setCity($this->getReference('city_spb'))
            ->setRating(500)
        ;

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($user)
        ;

        $user->setPassword($encoder->encodePassword('password', $user->getSalt()))
            ->setRatingGroup($this->getReference("rating_groups[" . $this->getRatinGroup($user->getRating()) . "]"))
            ->setGroups([$this->getReference("groups[Менеджеры]")])
        ;

        $manager->persist($user);

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 23;
    }

    public function getRatinGroup($rating)
    {
        if ($rating < 100) {
            return 0;
        }

        if ($rating > 499) {
            return 2;
        }

        return 1;
    }
}
