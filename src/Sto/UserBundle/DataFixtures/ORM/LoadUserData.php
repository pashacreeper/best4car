<?php

namespace Sto\UserBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Sto\UserBundle\Entity\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $firstNames = array('John', 'Mark', 'Lenny', 'Robert', 'Denial');
        $lastNames = array('McCormack', 'Clapton', 'Plant', 'Woodman', 'Brown', 'Young');

        for ($j=1; $j < 30; $j++) {
            shuffle($firstNames);
            shuffle($lastNames);

            foreach (array('user','admin') as $i => $role) {
                $user = new User();
                $user->setUsername(($j == 1 ? $role :  $role . $j ));
                $user->setEmail(($j == 1 ? $role :  $role . $j ) . "@sto.com");

                $user->setFirstName($firstNames[rand(0,4)]);
                $user->setLastName($lastNames[rand(0,5)]);
                $user->setPhoneNumber('+' . rand(123, 987) . ' ' . rand(12, 98) . ' ' . rand(123, 987) . '-' . rand(12, 98) . '-' . rand(21, 89));
                $user->setEnabled(($j == 1 ? 1 : rand(0, 1) ));
                $user->setRating( rand(1, 67) );

                $user->addRole("ROLE_" . strtoupper($role));
                // $user->setEnabled(1);

                $encoder = $this->container
                    ->get('security.encoder_factory')
                    ->getEncoder($user)
                ;
                $user->setPassword($encoder->encodePassword($role, $user->getSalt()));

                $manager->persist($user);
            }
        }

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
