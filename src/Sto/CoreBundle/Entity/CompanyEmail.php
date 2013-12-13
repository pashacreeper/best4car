<?php
namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity()
* @ORM\Table(name="company_emails")
*/
class CompanyEmail
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="Sto\CoreBundle\Entity\Company", inversedBy="emails")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * Set email
     *
     * @param  string       $email
     * @return CompanyEmail
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

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
     * Set company
     *
     * @param  \Sto\CoreBundle\Entity\Company $company
     * @return CompanyEmail
     */
    public function setCompany(\Sto\CoreBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \Sto\CoreBundle\Entity\Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set description
     *
     * @param  string       $description
     * @return CompanyEmail
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
