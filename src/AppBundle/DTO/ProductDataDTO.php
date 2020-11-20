<?php declare(strict_types=1);

namespace AppBundle\DTO;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ProductDataDTO
 * @package AppBundle\DTO
 * @Assert\Expression(
 * "this.costGBP > 5 or this.stock > 10",
 * message="The product costs less that $5 and has less than 10 stock"
 * )
 */
class ProductDataDTO
{
    /**
     * @Assert\NotBlank
     */
    public $productCode;

    /**
     * @Assert\NotBlank
     */
    public $productName;

    /**
     * @Assert\NotBlank
     */
    public $productDescription;

    /**
     * @Assert\NotBlank
     */
    public $stock;

    /**
     * @Assert\NotBlank
     * @Assert\LessThan(value="1000", message="The product cost over $1000")
     */
    public $costGBP;

    public $discontinued;
}