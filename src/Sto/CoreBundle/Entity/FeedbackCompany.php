<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FeedbackCompany
 *
 * @ORM\Entity()
 */
class FeedbackCompany extends Feedback
{
    /**
     * @ORM\Column(name="company_rating", type="integer")
     */
    private $companyRating;

    /**
     * @ORM\Column(name="company_id", type="integer", nullable=true)
     */
    private $companyId;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="feedbacks")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * Set comapnyRating
     *
     * @param  integer  $comapnyRating
     * @return Feedback
     */
    public function setCompanyRating($companyRating)
    {
        $this->companyRating = $companyRating;

        return $this;
    }

    /**
     * Get comapnyRating
     *
     * @return integer
     */
    public function getCompanyRating()
    {
        return $this->companyRating;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany(Company $company)
    {
        $this->company = $company;

        return $this;
    }
}
