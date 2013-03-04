<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface,
    Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\FixtureInterface,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\UserBundle\Entity\RatingGroup;

class LoadRatingGroupData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $groups = [
                'Автолюбители'      => [10, 99],
                'Автоэксперты'   => [100, 499],
                'Автопрофи'      => [500, 100000],
            ];

        $i = 0;
        foreach ($groups as $name => $ratings) {
            $group = new RatingGroup();
            $group->setName($name);
            $group->setMinRating($ratings[0]);
            $group->setMaxRating($ratings[1]);
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
        return 3;
    }
}
