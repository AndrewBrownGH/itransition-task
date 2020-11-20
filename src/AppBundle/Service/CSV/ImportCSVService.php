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
        $this->csv = $csv;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->report = new ImportCSVReport();
    }

    /**
     * @param string $path
     * @param bool $testMode
     * @return ImportCSVReport
     */
    public function import(string $path, bool $testMode = false): ImportCSVReport
    {
        $this->csv->parse($path);
        $rows = $this->csv->data;

        foreach ($rows as $row) {
            try {
                $this->report->increaseProcessed();

                /** @var ProductDataDTO $productDataDTO */
                $productDataDTO = $this->serializer->denormalize($row, ProductDataDTO::class);
                $this->validator->validate($productDataDTO);

                $productData = new ProductData(
                    $productDataDTO->productCode,
                    $productDataDTO->productName,
                    $productDataDTO->productDescription,
                    $productDataDTO->stock,
                    $productDataDTO->costGBP,
                    $productDataDTO->discontinued
                );
                $this->validator->validate($productData);

                if ($testMode) {   //if enabled test mode, we don't import the products.
                    continue;
                }

                $this->entityManager->persist($productData);
                $this->entityManager->flush();
            } catch (Exception $e) {
                $product = implode($this->csv->delimiter, $row);
                $this->report->addError($e->getMessage(), $product);
            }
        }

        return $this->report;
    }
}