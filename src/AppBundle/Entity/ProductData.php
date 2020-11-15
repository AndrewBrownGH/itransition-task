<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use DateTime;

/**
 * ProductData
 *
 * @ORM\Table(name="tblProductData")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductDataRepository")
 */
class ProductData
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $intProductDataId;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductCode", type="string", length=10, unique=true)
     */
    private $strProductCode;
    /**
     * @var string
     *
     * @ORM\Column(name="strProductName", type="string", length=50)
     */
    private $strProductName;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductDesc", type="string", length=255)
     */
    private $strProductDesc;
    /**
     * @var int
     *
     * @ORM\Column(name="intStock", type="integer")
     */
    private $intStock;

    /**
     * @var string
     *
     * @ORM\Column(name="decimalCostGBP", type="decimal", precision=5, scale=2)
     */
    private $decimalCostGBP;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dtmAdded", type="datetime", nullable=true)
     */
    private $dtmAdded;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    private $dtmDiscontinued;

    public function __construct(
        string $strProductCode,
        string $strProductName,
        string $strProductDesc,
        int $intStock,
        float $decimalCostGBP,
        string $dtmDiscontinued
    )
    {
        if ($decimalCostGBP < 5 && $intStock < 10) {
            throw new Exception('The product costs less that $5 and has less than 10 stock');
        }
        if ($decimalCostGBP > 1000) {
            throw new Exception('The product cost over $1000');
        }

        $this->strProductCode = $strProductCode;
        $this->strProductName = $strProductName;
        $this->strProductDesc = $strProductDesc;
        $this->intStock = $intStock;
        $this->decimalCostGBP = $decimalCostGBP;
        $this->dtmDiscontinued = $dtmDiscontinued === 'yes' ? new DateTime() : null;
        $this->dtmAdded = new DateTime();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getIntProductDataId()
    {
        return $this->intProductDataId;
    }


    /**
     * Set strProductName
     *
     * @param string $strProductName
     *
     * @return ProductData
     */
    public function setStrProductName($strProductName)
    {
        $this->strProductName = $strProductName;

        return $this;
    }

    /**
     * Get strProductName
     *
     * @return string
     */
    public function getStrProductName()
    {
        return $this->strProductName;
    }

    /**
     * Set strProductDesc
     *
     * @param string $strProductDesc
     *
     * @return ProductData
     */
    public function setStrProductDesc($strProductDesc)
    {
        $this->strProductDesc = $strProductDesc;

        return $this;
    }

    /**
     * Get strProductDesc
     *
     * @return string
     */
    public function getStrProductDesc()
    {
        return $this->strProductDesc;
    }

    /**
     * Set strProductCode
     *
     * @param string $strProductCode
     *
     * @return ProductData
     */
    public function setStrProductCode($strProductCode)
    {
        $this->strProductCode = $strProductCode;

        return $this;
    }

    /**
     * Get strProductCode
     *
     * @return string
     */
    public function getStrProductCode()
    {
        return $this->strProductCode;
    }

    /**
     * Set dtmAdded
     *
     * @param \DateTime $dtmAdded
     *
     * @return ProductData
     */
    public function setDtmAdded($dtmAdded)
    {
        $this->dtmAdded = $dtmAdded;

        return $this;
    }

    /**
     * Get dtmAdded
     *
     * @return \DateTime
     */
    public function getDtmAdded()
    {
        return $this->dtmAdded;
    }

    /**
     * Set dtmDiscontinued
     *
     * @param \DateTime $dtmDiscontinued
     *
     * @return ProductData
     */
    public function setDtmDiscontinued($dtmDiscontinued)
    {
        $this->dtmDiscontinued = $dtmDiscontinued;

        return $this;
    }

    /**
     * Get dtmDiscontinued
     *
     * @return \DateTime
     */
    public function getDtmDiscontinued()
    {
        return $this->dtmDiscontinued;
    }

    /**
     * Set intStock
     *
     * @param integer $intStock
     *
     * @return ProductData
     */
    public function setIntStock($intStock)
    {
        $this->intStock = $intStock;

        return $this;
    }

    /**
     * Get intStock
     *
     * @return int
     */
    public function getIntStock()
    {
        return $this->intStock;
    }

    /**
     * Set decimalCostGBP
     *
     * @param string $decimalCostGBP
     *
     * @return ProductData
     */
    public function setDecimalCostGBP($decimalCostGBP)
    {
        $this->decimalCostGBP = $decimalCostGBP;

        return $this;
    }

    /**
     * Get decimalCostGBP
     *
     * @return string
     */
    public function getDecimalCostGBP()
    {
        return $this->decimalCostGBP;
    }
}

