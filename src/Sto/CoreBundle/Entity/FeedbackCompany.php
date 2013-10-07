<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sto\UserBundle\Entity\User;

/**
 * FeedbackCompany
 *
 * @ORM\Entity()
 */
class FeedbackCompany extends Feedback
{
    /**
     * @ORM\Column(name="price_level_id", type="integer")
     */
    private $priceLevelId;

    /**
     * @ORM\ManyToOne(targetEntity="\Sto\CoreBundle\Entity\Dictionary\PriceLevel")
     * @ORM\JoinColumn(name="price_level_id", referencedColumnName="id")
     */
    private $priceLevel;

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

    public function __construct(User $user = null, Company $company = null)
    {
        parent::__construct($user);

        if ($company) {
            $this->setCompany($company);
        }
    }

    /**
     * Set PriceLevel
     */
    public function setPriceLevel(\Sto\CoreBundle\Entity\Dictionary\PriceLevel $price)
    {
        $this->priceLevel = $price;

        return $this;
    }

    /**
     * Get PriceLevel
     */
    public function getPriceLevel()
    {
        return $this->priceLevel;
    }

    /**
     * Set comapnyRating
     */
    public function setCompanyRating($companyRating)
    {
        $this->companyRating = $companyRating;

        return $this;
    }

    /**
     * Get comapnyRating
     */
    public function getCompanyRating()
    {
        return $this->companyRating;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany(Company $company)
    {
        $this->company = $company;
        $this->companyId = $company->getId();

        return $this;
    }

    /**
     * Set priceLevelId
     *
     * @param  integer         $priceLevelId
     * @return FeedbackCompany
     */
    public function setPriceLevelId($priceLevelId)
    {
        $this->priceLevelId = $priceLevelId;

        return $this;
    }

    /**
     * Get priceLevelId
     *
     * @return integer
     */
    public function getPriceLevelId()
    {
        return $this->priceLevelId;
    }
}
