<?php

namespace Sto\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User Contacts
 *
 * @ORM\Entity
 * @ORM\Table(name="user_contacts")
 */
class UserContacts
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="value", type="string")
     */
    private $value;

    /**
     * @var integer
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @ORM\ManyToOne(targetEntity="\Sto\UserBundle\Entity\User", inversedBy="contacts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var integer
     * @ORM\Column(name="type_id", type="integer", nullable=true)
     */
    private $typeId;
    /**
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\Dictionary\ContactType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string      $name
     * @return RatingGroup
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set userId
     *
     * @param  integer     $userId
     * @return RatingGroup
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Add user
     *
     * @param  \Sto\UserBundle\Entity\User $user
     * @return RatingGroup
     */
    public function setUser(\Sto\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getTypeId()
    {
        return $this->typeId;
    }

    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getType()
    {
        return $this->type;
    }

    public function setType(\Sto\CoreBundle\Entity\Dictionary\ContactType $type)
    {
        $this->type = $type;

        return $this;
    }

    public function __toString()
    {
        return "{$this->type}: {$this->value}";
    }
}
