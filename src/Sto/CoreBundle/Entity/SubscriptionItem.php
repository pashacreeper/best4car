<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="subscription_items")
 * @ORM\Entity
 */
class SubscriptionItem
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\Subscription", inversedBy="subscription_items")
     * @ORM\JoinColumn(name="subscription_id", referencedColumnName="id")
     */
    private $subscription;

    /**
     * @ORM\Column(name="type", type="string", length=100)
     */
    private $type;

    /**
     * @ORM\Column(name="item_id", type="integer")
     */
    private $itemId;

    /**
     * @ORM\Column(name="read", type="boolean")
     */
    private $read;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param mixed $itemId
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
    }

    /**
     * @return mixed
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @param mixed $subscription
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * @param mixed $read
     */
    public function setRead($read)
    {
        $this->read = $read;
    }
}
