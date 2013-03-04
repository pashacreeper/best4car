<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface,
    Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\FixtureInterface,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\UserBundle\Entity\Group;

class LoadGroupsData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $groups = [
                'Замороженные'      => ['ROLE_FROZEN'],
                'Заблокированные'   => ['ROLE_BANNED'],
                'Пользователи'      => ['ROLE_USER'],
                'Менеджеры'         => ['ROLE_MANAGER', 'ROLE_USER'],
                'Редакторы'         => ['ROLE_EDITOR', 'ROLE_USER'],
                'Модераторы'        => ['ROLE_MODERATOR', 'ROLE_USER'],
                'Администраторы'    => ['ROLE_ADMIN', 'ROLE_MODERATOR']
            ];

        $i = 0;
        foreach ($groups as $name => $roles) {
            $group = new Group($name);
            foreach ($roles as $role) {
                $group->addRole($role);
            }
            $manager->persist($group);
            $this->addReference("groups[{$i}]", $group);
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
        return 2;
    }
}
