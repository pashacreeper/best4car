<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Dictionary;

class LoadDictionaryContactTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $currencys = [
            '' => 'Сайт',
            'mailto:' => 'Email',
            'skype:' => 'Skype',
            'vk.com' => 'Вконтакте',
            'facebook.com' => 'Facebook',
            'twitter.com' => 'Twitter'
        ];

        $i = 0;
        foreach ($currencys as $prefix => $name) {
            $dictionary = (new Dictionary\ContactType)
                ->setPrefix($prefix)
                ->setName($name)
            ;

            $manager->persist($dictionary);
            $this->addReference("contact_types[{$i}]", $dictionary);
            $i++;
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 19;
    }
}
