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

    protected $mailNotificator;

    public function __construct(ObjectManager $em, $mailNotificator)
    {
        $this->em = $em;
        $this->mailNotificator = $mailNotificator;
    }

    public function createOnItem($item, $now = true)
    {
        $feed = new FeedItem();
        if ($item instanceof Company) {
            $feed->setCompany($item);
            if (!$now) {
                $feed->setCreatedAt($item->getCreatetDate());
            }
        }

        if ($item instanceof Deal) {
            $feed->setDeal($item);
            if (!$now) {
                $feed->setCreatedAt($item->getCreatedAt());
            }
        }

        $this->em->persist($feed);
        $this->em->flush();

        $this->notifyUsers($feed);

        return $feed;
    }

    protected function notifyUsers($feed)
    {
        $users = $this->em->getRepository('StoUserBundle:User')->findForFeedNotify($feed);

        $this->mailNotificator->sendFeedNotify($users, $feed);
    }
}
