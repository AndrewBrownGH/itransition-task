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
        $this->csv = $csv;
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->report = new ImportCSVReport();
    }

    public function import(string $path, string $delimiter = null, bool $testMode = false): ImportCSVReport
    {
        $rows = $this->parse($path, $delimiter);

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

                if ($testMode) {   //if enabled test mode, we won't import the products.
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
}