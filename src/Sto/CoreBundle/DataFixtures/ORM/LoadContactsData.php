<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\UserBundle\Entity\Contacts;

class LoadContactsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $contacts = ['bla-bla', 'qwertyui'];

        for ($i=0; $i<10; $i++) {
            $contact = (new Contacts)
                ->setValue($contacts[rand(0,1)])
                ->setUser($this->getReference('manager['.rand(1,3).']'))
                ->setType($this->getReference('contact_types['.rand(0,2).']'))
            ;

            $manager->persist($contact);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 29;
    }
}
