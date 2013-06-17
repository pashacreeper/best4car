<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
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
            'Администраторы'    => ['ROLE_ADMIN', 'ROLE_MODERATOR', 'ROLE_EDITOR', 'ROLE_MANAGER']
        ];

        foreach ($groups as $name => $roles) {
            $group = new Group($name);
            foreach ($roles as $role) {
                $group->addRole($role);
            }
            $manager->persist($group);
            $this->addReference("groups[{$name}]", $group);
        }
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 21;
    }
}
