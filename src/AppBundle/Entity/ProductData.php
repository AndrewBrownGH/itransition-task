<?php declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use DateTime;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ProductData
 *
 * @ORM\Table(name="tblProductData")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductDataRepository")
 * @UniqueEntity("productCode", message="Product code is already used")
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
    private $productDataId;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductCode", type="string", length=10, unique=true)
     */
    private $productCode;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductName", type="string", length=50)
     */
    private $productName;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductDesc", type="string", length=255)
     */
    private $productDescription;

    /**
     * @var int
     *
     * @ORM\Column(name="intStock", type="integer")
     */
    private $stock;

    /**
     * @var float
     *
     * @ORM\Column(name="decimalCostGBP", type="decimal", precision=5, scale=2)
     */
    private $costGBP;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dtmAdded", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    private $discontinued;

    public function __construct(
        string $productCode,
        string $productName,
        string $productDescription,
        int $stock,
        float $costGBP,
        bool $discontinued
    )
    {
        $this->productCode = $productCode;
        $this->productName = $productName;
        $this->productDescription = $productDescription;
        $this->stock = $stock;
        $this->costGBP = $costGBP;
        $this->discontinued = $discontinued === true ? new DateTime() : null;
        $this->createdAt = new DateTime();
    }

    public function getProductDataId(): int
    {
        return $this->productDataId;
    }

    public function setProductName(string $productName): ProductData
    {
        $this->productName = $productName;
        return $this;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductDescription(string $productDescription): ProductData
    {
        $this->productDescription = $productDescription;
        return $this;
    }

    public function getProductDescription(): string
    {
        return $this->productDescription;
    }

    public function setProductCode(string $productCode): ProductData
    {
        $this->productCode = $productCode;
        return $this;
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }

    public function setCreatedAt(DateTime $createdAt): ProductData
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setDiscontinued(DateTime $discontinued): ProductData
    {
        $this->discontinued = $discontinued;
        return $this;
    }

    public function getDiscontinued(): DateTime
    {
        return $this->discontinued;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): ProductData
    {
        $this->stock = $stock;
        return $this;
    }

    public function getCostGBP(): float
    {
        return $this->costGBP;
    }

    public function setCostGBP(float $costGBP): ProductData
    {
        $this->costGBP = $costGBP;
        return $this;
    }
}

