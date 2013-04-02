<?php

namespace Sto\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sto\CoreBundle\Entity\AutoCatalog;

/**
 * AutoCataloItem
 *
 * @ORM\Entity()
 */
class AutoCatalogItem extends AutoCatalog
{

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, name="body_type", nullable=true)
     */
    protected $bodyType;

    /**
     * @var string $engineVolume
     *
     * @ORM\Column(type="string", length=255, name="engine_volume", nullable=true)
     */
    protected $engineVolume;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, name="power", nullable=true)
     */
    protected $power;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, name="privod", nullable=true)
     */
    protected $privod;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, name="transmission", nullable=true)
     */
    protected $transmission;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, name="transmission_count", nullable=true)
     */
    protected $transmissionCount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, name="start_production", nullable=true)
     */
    protected $startProduction;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, name="end_production", nullable=true)
     */
    protected $endProduction;


    /**
     * @param string $bodyType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $bodyType
     */
    public function setBodyType($bodyType)
    {
        $this->bodyType = $bodyType;

        return $this;
    }

    /**
     * @return string
     */
    public function getBodyType()
    {
        return $this->bodyType;
    }


    /**
     * @param string $engineVolume
     */
    public function setEngineVolume($engineVolume)
    {
        $this->engineVolume = $engineVolume;

        return $this;
    }

    /**
     * @return string
     */
    public function getEngineVolume()
    {
        return $this->engineVolume;
    }

    public function setPower($power){
        $this->power = $power;

        return $this;
    }

    public function getPower(){
        return $this->power;
    }

    public function setPrivod($privod){
        $this->privod = $privod;

        return $this;
    }

    public function getPrivod(){
        return $this->privod;
    }

    public function setTransmission($transmission){
        $this->transmission = $transmission;

        return $this;
    }

    public function getTransmission(){
        return $this->transmission;
    }

    public function setTransmissionCount($count){
        $this->transmissionCount = $count;

        return $this;
    }

    public function getTransmissionCount(){
        return $this->transmissionCount;
    }

    public function setStartProduction($startProduction){
        $this->startProduction = $startProduction;

        return $this;
    }

    public function getStartProduction(){
        return $this->startProduction;
    }

    public function setEndProduction($endProduction){
        $this->endProduction= $endProduction;

        return $this;
    }

    public function getEndProduction(){
        return $this->endProduction;
    }


}
