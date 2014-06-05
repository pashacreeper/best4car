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

    public function createOnItem($item, $now = true)
    {
        $feed = new FeedItem();
        if ($item instanceof Company) {
            $feed->setCompany($item);
            if(!$now) {
                $feed->setCreatedAt($item->getCreatetDate());
            }
        }

        if ($item instanceof Deal) {
            $feed->setDeal($item);
            if(!$now) {
                $feed->setCreatedAt($item->getCreatedAt());
            }
        }

        $this->em->persist($feed);
        $this->em->flush();

        return $feed;
    }
}
