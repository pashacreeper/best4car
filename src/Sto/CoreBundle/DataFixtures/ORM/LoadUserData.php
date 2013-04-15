<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface,
    Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\FixtureInterface,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $firstNames = ['John', 'Mark', 'Lenny', 'Robert', 'Denial'];
        $lastNames = ['McCormack', 'Clapton', 'Plant', 'Woodman', 'Brown', 'Young'];
        $roles = ['user', 'admin', 'manager'];
        $cities = ['Москва','Челябинск','Красноярск','Владивосток'];

        for ($j=1; $j < 31; $j++) {
            shuffle($firstNames);
            shuffle($lastNames);
            $role = $j == 1 ? $roles[1] : $roles[rand(0,2)];
            $rating = rand(10, 600);
            if ($rating<100)
                $rating_group_id = 0;
            elseif ($rating>499)
                $rating_group_id = 2;
            else
                $rating_group_id = 1;

            $user = new User;
            $user->setUsername(($j == 1 ? $role :  $role . $j ));
            $user->setEmail(($j == 1 ? $role :  $role . $j ) . "@sto.com");
            $user->setFirstName($firstNames[rand(0,4)]);
            $user->setLastName($lastNames[rand(0,5)]);
            $user->setPhoneNumber('+7 (' . rand(123, 987) .') ' . rand(123, 987) . '-' . rand(12, 98). '-' . rand(12, 98));
            $user->setEnabled(($j == 1 ? 1 : rand(0, 1) ));
            $user->setRating( $rating );
            $user->setLinkGarage( '#' );
            $user->setRatingGroup($this->getReference("rating_groups[".$rating_group_id."]"));
            $user->setGroups([$this->getReference("groups[".($j == 1 ? 6 :  rand(0,5) )."]")]);
            $user->setCity($cities[rand(0,3)]);
            $encoder = $this->container
                ->get('security.encoder_factory')
                ->getEncoder($user)
            ;
            $user->setPassword($encoder->encodePassword($role, $user->getSalt()));

            $manager->persist($user);
            $this->addReference("user[{$j}]", $user);
        }

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 81;
    }
}
