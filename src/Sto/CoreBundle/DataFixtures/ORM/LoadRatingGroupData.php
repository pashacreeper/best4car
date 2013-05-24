<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\UserBundle\Entity\RatingGroup;

class LoadRatingGroupData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $groups = [
            'Автолюбители' => [10, 99, 1],
            'Автоэксперты' => [100, 499, 3],
            'Автопрофи'    => [500, 100000, 7],
        ];

        $i = 0;
        foreach ($groups as $name => $ratings) {
            $group = new RatingGroup();
            $group->setName($name);
            $group->setMinRating($ratings[0]);
            $group->setMaxRating($ratings[1]);
            $group->setMultiplier($ratings[2]);
            $manager->persist($group);
            $this->addReference("rating_groups[{$i}]", $group);
            $i++;
        }
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 22;
    }
}
