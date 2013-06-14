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

        for ($i=1; $i<39; $i++) {
            $companyManager = (new CompanyManager)
                ->setUser($this->getReference('user['.rand(1,10).']'))
                ->setCompany($this->getReference('company['.$i.']'))
                ->setPhone('+7 (' . rand(123, 987) .') ' . rand(123, 987) . '-' . rand(12, 98). '-' . rand(12, 98))
            ;

            $manager->persist($companyManager);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 32;
    }
}
