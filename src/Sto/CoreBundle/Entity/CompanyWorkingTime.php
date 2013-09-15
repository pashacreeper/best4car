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
    private $days_of_week  = [1, 2, 4, 8, 16, 32, 64];

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
    private $from;

    /**
     * @ORM\Column(type="time")
     */
    private $till;

    /**
     * @ORM\Column(type="integer")
     */
    private $days = 0;

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

    public function getTill()
    {
        return $this->till;
    }

    public function setTill($till)
    {
        $this->till = $till;

        return $this;
    }

    public function getDays()
    {
        $response = [];
        foreach ($this->days_of_week as $day) {
            $response[] = ($day & $this->days) > 0;
        }

        return $response;
    }

    public function setDays($days)
    {
        foreach ($days as $k => $day) {
            if ($day) {
                $this->days += pow(2, $k);
            }
        }

        return $this;
    }
}
