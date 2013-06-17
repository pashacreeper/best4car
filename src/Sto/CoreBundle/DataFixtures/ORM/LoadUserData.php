<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $firstNames = ['John', 'Mark', 'Lenny', 'Robert', 'Denial'];
        $lastNames = ['McCormack', 'Clapton', 'Plant', 'Woodman', 'Brown', 'Young'];
        $roles = ['Администраторы' => 'admin', 'Менеджеры' => 'manager', 'Модераторы' => 'moderator', 'Пользователи' => 'user', 'Замороженные' => 'frozen', 'Заблокированные' => 'banned'];
        $cities = ['Москва','Челябинск','Красноярск','Владивосток'];

        foreach ($roles as $group => $role) {
            for ($i=1; $i < 4; $i++) {
                $user = new User;
                $user->setUsername(($i == 1) ? $role : $role . $i)
                    ->setEmail((($i == 1) ? $role : $role . $i) . "@sto.com")
                    ->setFirstName($firstNames[rand(0,4)])
                    ->setLastName($lastNames[rand(0,5)])
                    ->setPhoneNumber('+7 (' . rand(123, 987) .') ' . rand(123, 987) . '-' . rand(12, 98). '-' . rand(12, 98))
                    ->setLinkGarage('#')
                    ->setCity($this->getReference('city[spb]'))
                    ->setRating(rand(10, 700))
                ;
                $encoder = $this->container
                    ->get('security.encoder_factory')
                    ->getEncoder($user)
                ;
                $user->setPassword($encoder->encodePassword($role, $user->getSalt()))
                    ->setRatingGroup($this->getReference("rating_groups[" . $this->getRatinGroup($user->getRating()) . "]"))
                    ->setGroups([$this->getReference("groups[{$group}]")])
                ;
                $manager->persist($user);
                $this->addReference("{$role}[{$i}]", $user);
            }
        }

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
