<?php
namespace Sto\CoreBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\Company;
use Sto\CoreBundle\Entity\Deal;
use Sto\CoreBundle\Entity\FeedItem;

class FeedManager
{
    /**
     * @var ObjectManager
     */
    protected $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    public function createOnItem($item)
    {
        $feed = new FeedItem();
        if ($item instanceof Company) {
            $feed->setCompany($item);
        }

        if ($item instanceof Deal) {
            $feed->setDeal($item);
        }

        $this->em->persist($feed);
        $this->em->flush();

        return $feed;
    }
}
