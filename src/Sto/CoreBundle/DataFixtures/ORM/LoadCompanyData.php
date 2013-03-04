<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use sto\CoreBundle\Entity\Company;

class LoadCompanyData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i < 39 ; $i++) {

            $rating = rand(10, 600);
            if ($rating<100)
                $rating_group_id = 0;
            elseif ($rating>499)
                $rating_group_id = 2;
            else
                $rating_group_id = 1;

            $company = new Company;
            $company->setName('Test company - ' . $i);
            $company->setSlogan('Slogan - ' . $i);
            $company->setFullName('Full name test company - ' . $i);
            $company->setWeb('www.test' . $i .'.ru');
            $company->setSpecialization('CТО, Паркинг, Мойка');
            $company->setServices('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $company->setAdditionalServices('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $company->setLogo('LOGO');
            $company->setWorkingTime('10:00 - 20:00');
            $company->setPhones('+7-' . '-' . rand(123, 987) . '-' . rand(1234, 9876));
            $company->setSkype('altauto');
            $company->setEmail('info@altauto.ru');
            $company->setAddress('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $company->setGps('77, 99');
            $company->setCreatetDate(new \DateTime(rand(1, 28) . '-' . rand(1, 12) . '-' . rand(2011, 2020)));
            $company->setPhotos('Photos');
            $company->setSocialNetworks('Facebook, Vk, Google+');
            $company->setRating($rating);
            $company->setReviews('1,2,3,4,5');
            $company->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $company->setSubscribable('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $company->setHourPrice(rand(500,4500) . ' р.');
            $company->setManagers('1, 2, 3, 4');
            $company->setAdministratorContactInfo('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $company->setVisible(true);
            $company->setNotes('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $company->setGroups([$this->getReference("groups[".rand(0,5)."]")]);
            $company->setRatingGroup($this->getReference("rating_groups[".$rating_group_id."]"));

            $manager->persist($company);
            $this->addReference("company[{$i}]", $company);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}
