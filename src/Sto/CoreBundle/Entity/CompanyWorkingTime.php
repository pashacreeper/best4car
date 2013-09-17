<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CompanyWorkingTime
 * @ORM\Table(name="company_working_time")
 * @ORM\Entity()
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
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\Company", inversedBy="workingTime")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * @ORM\Column(type="time")
     */
    private $fromTime;

    /**
     * @ORM\Column(type="time")
     */
    private $tillTime;

    /**
     * @ORM\Column(type="boolean")
     */
    private $days_monday = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $days_tuesday = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $days_wednesday = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $days_thursday = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $days_friday = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $days_saturday = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $days_sunday = false;

    public function getId()
    {
        return $this->id;
    }

    public function setFromTime($from)
    {
        $this->fromTime = $from;

        return $this;
    }

    public function getFromTime()
    {
        return $this->fromTime;
    }

    public function getTillTime()
    {
        return $this->tillTime;
    }

    public function setTillTime($till)
    {
        $this->tillTime = $till;

        return $this;
    }

    public function getDays()
    {
        return [$this->days_monday, $this->days_tuesday, $this->days_wednesday, $this->days_thursday, $this->days_friday, $this->days_saturday, $this->days_sunday];
    }

    public function setDays($days)
    {
        $this->days_monday = $days[0];
        $this->days_tuesday = $days[1];
        $this->days_wednesday = $days[2];
        $this->days_thursday = $days[3];
        $this->days_friday = $days[4];
        $this->days_saturday = $days[5];
        $this->days_sunday = $days[6];

        return $this;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($value)
    {
        $this->company = $value;

        return $this;
    }
}
