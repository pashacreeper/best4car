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
     * @var integer
     *
     * @ORM\Column(name="price_level_id", type="integer")
     */
    private $priceLevelId;

    /**
     * @var integer
     *
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

    /**
     * Set PriceLevel
     *
     * @param  entity  $price
     * @return Feedback
     */
    public function setPriceLevel(\Sto\CoreBundle\Entity\Dictionary\PriceLevel $price){
        $this->priceLevel = $price;

        return $this;
    }

    /**
     * Get PriceLevel
     *
     * @return entity
     */
    public function getPriceLevel(){
        return $this->priceLevel;
    }

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
