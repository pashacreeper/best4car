<?php

namespace Sto\CoreBundle\Service;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Sto\CoreBundle\Entity\FeedbackCompany;
use Sto\CoreBundle\Entity\FeedbackDeal;

/**
 * Class FeedbackNumberListener
 *
 * @package Sto\CoreBundle\Service
 */
class FeedbackNumberListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->setFeedbackNumber($args);
    }

    /**
     * Set feedback number
     *
     * @param LifecycleEventArgs $args
     *
     * @return null
     */
    protected function setFeedbackNumber($args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof FeedbackCompany) {
            $em = $args->getEntityManager();
            $feedbackNumber = (int) $em->getRepository('StoCoreBundle:Feedback')->getMaxFeedbackNumberByCompany($entity->getCompany());
            $entity->setFeedbackNumber($feedbackNumber + 1);
        }
        if ($entity instanceof FeedbackDeal) {
            $em = $args->getEntityManager();
            $feedbackNumber = (int) $em->getRepository('StoCoreBundle:Feedback')->getMaxFeedbackNumberByDeal($entity->getDeal());
            $entity->setFeedbackNumber($feedbackNumber + 1);
        }
    }
}
