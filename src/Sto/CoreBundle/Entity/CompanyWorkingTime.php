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
     * @ORM\Column(type="boolean", name="days_monday")
     */
    private $daysMonday = false;

    /**
     * @ORM\Column(type="boolean", name="days_tuesday")
     */
    private $daysTuesday = false;

    /**
     * @ORM\Column(type="boolean", name="days_wednesday")
     */
    private $daysWednesday = false;

    /**
     * @ORM\Column(type="boolean", name="days_thursday")
     */
    private $daysThursday = false;

    /**
     * @ORM\Column(type="boolean", name="days_friday")
     */
    private $daysFriday = false;

    /**
     * @ORM\Column(type="boolean", name="days_saturday")
     */
    private $daysSaturday = false;

    /**
     * @ORM\Column(type="boolean", name="days_sunday")
     */
    private $daysSunday = false;

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
        return [
            $this->daysMonday,
            $this->daysTuesday,
            $this->daysWednesday,
            $this->daysThursday,
            $this->daysFriday,
            $this->daysSaturday,
            $this->daysSunday
        ];
    }

    public function setDays($days)
    {
        $this->daysMonday = $days[0];
        $this->daysTuesday = $days[1];
        $this->daysWednesday = $days[2];
        $this->daysThursday = $days[3];
        $this->daysFriday = $days[4];
        $this->daysSaturday = $days[5];
        $this->daysSunday = $days[6];

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
