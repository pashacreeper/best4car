<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\CompanyManager;

class LoadCompanyManagerData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($j = 1; $j <= rand(1,3); $j++) {
            for ($i = 1; $i < 4; $i++) {
                    $companyManager = (new CompanyManager)
                    ->setUser($this->getReference('manager[' . $j . ']'))
                    ->setCompany($this->getReference('company[' . rand(1, 38) . ']'))
                    ->setPhone('+7 (' . rand(123, 987) .') ' . rand(123, 987) . '-' . rand(12, 98). '-' . rand(12, 98))
                ;
            }
            $manager->persist($companyManager);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 32;
    }
}
