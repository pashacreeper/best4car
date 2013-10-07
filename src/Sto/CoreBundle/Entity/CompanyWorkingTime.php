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

    /**
     * Set daysMonday
     *
     * @param  boolean            $daysMonday
     * @return CompanyWorkingTime
     */
    public function setDaysMonday($daysMonday)
    {
        $this->daysMonday = $daysMonday;

        return $this;
    }

    /**
     * Get daysMonday
     *
     * @return boolean
     */
    public function getDaysMonday()
    {
        return $this->daysMonday;
    }

    /**
     * Set daysTuesday
     *
     * @param  boolean            $daysTuesday
     * @return CompanyWorkingTime
     */
    public function setDaysTuesday($daysTuesday)
    {
        $this->daysTuesday = $daysTuesday;

        return $this;
    }

    /**
     * Get daysTuesday
     *
     * @return boolean
     */
    public function getDaysTuesday()
    {
        return $this->daysTuesday;
    }

    /**
     * Set daysWednesday
     *
     * @param  boolean            $daysWednesday
     * @return CompanyWorkingTime
     */
    public function setDaysWednesday($daysWednesday)
    {
        $this->daysWednesday = $daysWednesday;

        return $this;
    }

    /**
     * Get daysWednesday
     *
     * @return boolean
     */
    public function getDaysWednesday()
    {
        return $this->daysWednesday;
    }

    /**
     * Set daysThursday
     *
     * @param  boolean            $daysThursday
     * @return CompanyWorkingTime
     */
    public function setDaysThursday($daysThursday)
    {
        $this->daysThursday = $daysThursday;

        return $this;
    }

    /**
     * Get daysThursday
     *
     * @return boolean
     */
    public function getDaysThursday()
    {
        return $this->daysThursday;
    }

    /**
     * Set daysFriday
     *
     * @param  boolean            $daysFriday
     * @return CompanyWorkingTime
     */
    public function setDaysFriday($daysFriday)
    {
        $this->daysFriday = $daysFriday;

        return $this;
    }

    /**
     * Get daysFriday
     *
     * @return boolean
     */
    public function getDaysFriday()
    {
        return $this->daysFriday;
    }

    /**
     * Set daysSaturday
     *
     * @param  boolean            $daysSaturday
     * @return CompanyWorkingTime
     */
    public function setDaysSaturday($daysSaturday)
    {
        $this->daysSaturday = $daysSaturday;

        return $this;
    }

    /**
     * Get daysSaturday
     *
     * @return boolean
     */
    public function getDaysSaturday()
    {
        return $this->daysSaturday;
    }

    /**
     * Set daysSunday
     *
     * @param  boolean            $daysSunday
     * @return CompanyWorkingTime
     */
    public function setDaysSunday($daysSunday)
    {
        $this->daysSunday = $daysSunday;

        return $this;
    }

    /**
     * Get daysSunday
     *
     * @return boolean
     */
    public function getDaysSunday()
    {
        return $this->daysSunday;
    }
}
