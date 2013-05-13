<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Deal;

class LoadDealData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $types = ['Скидка', 'Маркетинговое мероприятие', 'Тест-драйв', 'Презентация, день открытых дверей.', 'Распродажа', 'Сезонное предложение'];

        for ($i=1; $i <= 5 ; $i++) {
            $deal = new Deal;
            $deal->setName('Test deal - ' . $i);
            $deal->setCompany($this->getReference("company[" . rand(1, 2) . "]"));
            $deal->setStartDate(new \DateTime(rand(1, 28) . '-' . rand(1, 12) . '-' . rand(2011, 2020)));
            $deal->setEndDate(new \DateTime(rand(1, 28) . '-' . rand(1, 12) . '-' . rand(2011, 2020)));
            $deal->setStartTime(new \DateTime(rand(1, 28) . '-' . rand(1, 12) . '-' . rand(2011, 2020) . ' ' . rand(0, 23) . ':' . rand(0, 59)));
            $deal->setEndTime(new \DateTime(rand(1, 28) . '-' . rand(1, 12) . '-' . rand(2011, 2020) . ' ' . rand(0, 23) . ':' . rand(0, 59)));
            $deal->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit fuga sunt provident vel numquam voluptates maxime eius quos nobis. Et quidem distinctio repellat voluptate accusamus beatae repellendus fuga nemo tenetur.');
            $deal->setType($this->getReference("dealsTypes[" . rand(0,5) . "]"));

            chdir(__DIR__ . '/../../../../../');
            $from = "app/Resources/fixtures/deals/".rand(1,20).".png";
            $to = "web/storage/images/deal_image/". $i .".png";

            if (!file_exists($from))
                $from = "app/Resources/fixtures/deals/2.png";

            if (!is_dir(dirname($to))) {
                mkdir(dirname($to), 0755, true);
            }

            if (!file_exists($to)) {
                copy($from, $to);
            }

            $deal->setImageName($i . '.png');

            $manager->persist($deal);
            $this->addReference("deal[{$i}]", $deal);
        }

        for ($i=6; $i <= 40 ; $i++) {
            $deal = new Deal;
            $deal->setName('Test deal - ' . $i);
            $deal->setCompany($this->getReference("company[" . rand(1, 38) . "]"));
            $deal->setStartDate(new \DateTime(rand(1, 28) . '-' . rand(1, 12) . '-' . rand(2011, 2020)));
            $deal->setEndDate(new \DateTime(rand(1, 28) . '-' . rand(1, 12) . '-' . rand(2011, 2020)));
            $deal->setStartTime(new \DateTime(rand(1, 28) . '-' . rand(1, 12) . '-' . rand(2011, 2020) . ' ' . rand(0, 23) . ':' . rand(0, 59)));
            $deal->setEndTime(new \DateTime(rand(1, 28) . '-' . rand(1, 12) . '-' . rand(2011, 2020) . ' ' . rand(0, 23) . ':' . rand(0, 59)));
            $deal->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit fuga sunt provident vel numquam voluptates maxime eius quos nobis. Et quidem distinctio repellat voluptate accusamus beatae repellendus fuga nemo tenetur.');

            chdir(__DIR__ . '/../../../../../');
            $from = "app/Resources/fixtures/deals/".rand(1,20).".png";
            $to = "web/storage/images/deal_image/". $i .".png";

            if (!file_exists($from))
                $from = "app/Resources/fixtures/deals/2.png";

            if (!is_dir(dirname($to))) {
                mkdir(dirname($to), 0755, true);
            }

            if (!file_exists($to)) {
                copy($from, $to);
            }

            $deal->setImageName($i . '.png');
            $deal->setType($this->getReference("dealsTypes[" . rand(0,5) . "]"));

            $manager->persist($deal);
            $this->addReference("deal[{$i}]", $deal);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 41;
    }
}
