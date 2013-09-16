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
     * @ORM\Column(type="integer")
     */
    private $days = 0;

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
        $response = [];
        foreach ([1, 2, 4, 8, 16, 32, 64] as $day) {
            $response[] = ($day & $this->days) > 0;
        }

        return $response;
    }

    public function setDays($days)
    {
        $this->days = 0;
        foreach ($days as $k => $day) {
            if ($day) {
                $this->days += pow(2, $k);
            }
        }

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
