<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\FeedbackAnswer;

class LoadFeedbackAnswerData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i < 41; $i++) {
            if (rand(1,10)<=7) {
                $feedbackAnswer = (new FeedbackAnswer)
                    ->setAnswer('Спасибо за Ваш отзыв! Будем рады видеть Вас снова!')
                    ->setDate(new \DateTime("now"))
                    ->setOwner($this->getReference("user[" . rand(1,30) . "]"))
                    ->setFeedback($this->getReference("feedback[" . $i . "]"))
                ;

                $manager->persist($feedbackAnswer);
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 52;
    }
}
