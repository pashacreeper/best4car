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
        $roles = ['user', 'admin'];

        for ($j=1; $j < 31; $j++) {
            shuffle($firstNames);
            shuffle($lastNames);
            $role = $j == 1 ? $roles[1] : $roles[rand(0,1)];

            $user = new User;
            $user->setUsername(($j == 1 ? $role :  $role . $j ));
            $user->setEmail(($j == 1 ? $role :  $role . $j ) . "@sto.com");
            $user->setFirstName($firstNames[rand(0,4)]);
            $user->setLastName($lastNames[rand(0,5)]);
            $user->setPhoneNumber('+' . rand(123, 987) . ' ' . rand(12, 98) . ' ' . rand(123, 987) . '-' . rand(12, 98) . '-' . rand(21, 89));
            $user->setEnabled(($j == 1 ? 1 : rand(0, 1) ));
            $user->setRating( rand(1, 67) );
            $user->addRole("ROLE_" . strtoupper($role));
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
        return 1;
    }
}
