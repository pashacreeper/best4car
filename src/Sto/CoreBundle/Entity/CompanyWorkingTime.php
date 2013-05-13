<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompanyWorkingTime
 */
class CompanyWorkingTime
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var time
     * @ORM\Column(type="time", nullable=true)
     */
    private $from;

    /**
     * @var time
     * @ORM\Column(type="time", nullable=true)
     */
    private $till;

    /**
     * @ORM\OneToMany(targetEntity="Dictionary\WeekDay", nullable=true)
     */
    private $dayFrom;

    /**
     * @ORM\OneToMany(targetEntity="Dictionary\WeekDay", nullable=true)
     */
    private $dayTill;

    public function getId()
    {
        return $this->id;
    }

    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setTill($till)
    {
        $this->till = $till;

        return $this;
    }

    public function setDayFrom($day)
    {
        $this->dayFrom = $day;

        return $this;
    }

    public function getDayFrom()
    {
        return $this->dayFrom;
    }

    public function setDayTill($day)
    {
        $this->dayTill = $day;

        return $this;
    }

    public function getDayTill()
    {
        return $this->dayTill;
    }
}
