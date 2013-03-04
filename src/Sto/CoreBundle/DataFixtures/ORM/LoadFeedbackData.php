<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Feedback,
    Sto\CoreBundle\Entity\FeedbackAnswer;

class LoadFeedbackData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $lastNames = ['Смирнов','Иванов','Кузнецов','Попов','Соколов'];

        for ($i=1; $i < 41; $i++) {
            $feedback = new Feedback;
            $feedback->setContent('Text content.');
            $feedback->setVisitDate(new \DateTime("now"));
            $feedback->setMastername($lastNames[rand(0,4)] . ' И.П.');
            $feedback->setCar('Mazda 3');
            $feedback->setStatenumber('А0' . rand(12,98) . 'АА ' . rand(123, 987));
            $feedback->setordernumber('З/Н № ' . rand(123456, 987654));
            $feedback->setCompanyRating(rand(1,5));
            $feedback->setFeedbackRating(rand(1,5)/rand(5,6));
            $feedback->setPluses('');
            $feedback->setMinuses('');
            $feedback->setTargetRating('СТО');
            $feedback->setPublished(rand(0,1));
            $feedback->setUser($this->getReference("user[" . rand(1,30) . "]"));
            $feedback->setCompany($this->getReference("company[" . rand(1,30) . "]"));

            $manager->persist($feedback);
            $this->addReference("feedback[{$i}]", $feedback);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 9;
    }
}
