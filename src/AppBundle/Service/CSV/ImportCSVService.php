<?php declare(strict_types=1);

namespace AppBundle\Service\CSV;

use AppBundle\DTO\ProductDataDTO;
use AppBundle\Entity\ProductData;
use AppBundle\Service\Validator\CustomValidator;
use Doctrine\ORM\EntityManager;
use ParseCsv\Csv;
use Exception;
use Symfony\Component\Serializer\Serializer;

class ImportCSVService
{
    private const VALID_DELIMITERS = [',', ' ', '\t', '\n', '\r'];

    private $entityManager;

    private $csv;

    private $validator;

    private $serializer;

    /**
     * @var ImportCSVReport
     */
    private $report;

    public function __construct(EntityManager $entityManager, CustomValidator $validator, Csv $csv, Serializer $serializer)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->csv = $csv;
        $this->serializer = $serializer;
        $this->report = new ImportCSVReport();
    }

    public function import(string $path, string $delimiter = null, bool $testMode = false): ImportCSVReport
    {
        $products = $this->parse($path, $delimiter);

        foreach ($products as $product) {
            try {
                $this->report->increaseProcessed();

                $productDataDTO = $this->createProductDataDTO($product);
                $isExistProduct = $this->isExistProduct($productDataDTO->productCode);

                $productData = $isExistProduct === true
                    ? $this->updateProductData($productDataDTO)
                    : $this->createProductData($productDataDTO);

                if ($testMode) {   //if enabled test mode, we won't import the products.
                    continue;
                }

                $this->entityManager->persist($productData);
                $this->entityManager->flush();
            } catch (Exception $e) {
                $product = implode($this->csv->delimiter, $product);
                $this->report->addError($e->getMessage(), $product);
            }
        }

        return $this->report;
    }

    private function parse(string $path, ?string $delimiter): array
    {
        if (in_array($delimiter, self::VALID_DELIMITERS)) {
            $this->csv->delimiter = $delimiter;
            $this->csv->parse($path);
        } else {
            $this->csv->auto($path);
        }

        return $this->csv->data;
    }

    private function createProductDataDTO(array $row): ProductDataDTO
    {
        /**
         * @var ProductDataDTO $productDataDTO
         */
        $productDataDTO = $this->serializer->denormalize($row, ProductDataDTO::class);
        $this->validator->validate($productDataDTO);

        return $productDataDTO;
    }

    private function createProductData(ProductDataDTO $productDataDTO): ProductData
    {
        return new ProductData(
            $productDataDTO->productCode,
            $productDataDTO->productName,
            $productDataDTO->productDescription,
            $productDataDTO->stock,
            $productDataDTO->costGBP,
            $productDataDTO->discontinued
        );
    }

    private function updateProductData(ProductDataDTO $productDataDTO): ProductData
    {
        $product = $this->entityManager->getRepository(ProductData::class)
            ->findProductByCode($productDataDTO->productCode);

        $product->setProductName($productDataDTO->productName);
        $product->setProductDescription($productDataDTO->productDescription);
        $product->setStock($productDataDTO->stock);
        $product->setCostGBP($productDataDTO->costGBP);
        $product->setDiscontinued($productDataDTO->discontinued);

        return $product;
    }

    private function isExistProduct($productCode): bool
    {
        $product = $this->entityManager->getRepository(ProductData::class)
            ->findProductByCode($productCode);

        return null !== $product;
    }
}