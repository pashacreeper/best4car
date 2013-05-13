<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\FeedbackCompany;
use Sto\CoreBundle\Entity\FeedbackDeal;

class LoadFeedbackData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $lastNames = ['Смирнов','Иванов','Кузнецов','Попов','Соколов'];

        for ($i=1; $i < 21; $i++) {
            $feedback = (new FeedbackCompany($this->getReference("user[" . rand(1,30) . "]")))
                ->setContent('Text content.')
                ->setVisitDate(new \DateTime("now"))
                // ->setMastername($lastNames[rand(0,4)] . ' И.П.')
                ->setCar('Mazda 3')
                ->setStatenumber('А0' . rand(12,98) . 'АА ' . rand(123, 987))
                ->setordernumber('З/Н № ' . rand(123456, 987654))
                ->setCompanyRating(rand(1,5))
                ->setFeedbackRating(rand(1,5))
                ->setPluses(rand(10, 200))
                ->setMinuses(rand(10, 200))
                ->setTargetRating('СТО')
                ->setPublished(rand(0,1))
                ->setCompany($this->getReference("company[" . rand(1,38) . "]"))
            ;

            $manager->persist($feedback);
            $this->addReference("feedback[{$i}]", $feedback);
        }

        for ($i=21; $i < 41; $i++) {
            $feedback = (new FeedbackDeal($this->getReference("user[" . rand(1,30) . "]")))
                ->setContent('Text content.')
                ->setVisitDate(new \DateTime("now"))
                // ->setMastername($lastNames[rand(0,4)] . ' И.П.')
                ->setCar('Mazda 3')
                ->setStatenumber('А0' . rand(12,98) . 'АА ' . rand(123, 987))
                ->setordernumber('З/Н № ' . rand(123456, 987654))
                ->setDealRating(rand(1,5))
                ->setFeedbackRating(rand(1,5))
                ->setPluses(rand(10, 200))
                ->setMinuses(rand(10, 200))
                ->setTargetRating('СТО')
                ->setPublished(rand(0,1))
                ->setDeal($this->getReference("deal[" . rand(1,40) . "]"))
            ;

            $manager->persist($feedback);
            $this->addReference("feedback[{$i}]", $feedback);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 51;
    }
}
