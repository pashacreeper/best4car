<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dictionary
 *
 * @ORM\Entity(repositoryClass="Sto\CoreBundle\Repository\DictionaryRepository")
 * @ORM\Table(name="dictionaries")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "company_type"       = "DictionaryCompanyType",
 *     "additional_service" = "DictionaryAdditionalService",
 *     "work"               = "DictionaryWork",
 *     "currency"           = "DictionaryCurrency",
 *     "country"            = "DictionaryCountry",
 *     "city"               = "DictionaryCity"
 * })
 */
class Dictionary
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="short_name", type="string", length=5, nullable=true)
     */
    private $shortName;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Dictionary", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Dictionary", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    private $parentId;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param  string     $code
     * @return Dictionary
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * Get name
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set name
     *
     * @param  string     $name
     * @return Dictionary
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set parentId
     *
     * @param  integer    $parentId
     * @return Dictionary
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set parent
     */
    public function setParent(Dictionary $parent = null)
    {
        $this->parent = $parent;
        if ($parent != null) {
            $this->parentId = $parent->getId();
        }

        return $this;
    }

    /**
     * Get parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     */
    public function addChildren(Dictionary $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     */
    public function removeChildren(Dictionary $children)
    {
        $this->children->removeElement($children);

        return $this;
    }

    /**
     * Get children
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function __toString()
    {
        return $this->name;
    }
}
