<?php declare(strict_types=1);

namespace AppBundle\Service\CSV;

use AppBundle\Entity\ProductData;
use Doctrine\ORM\EntityManager;
use ParseCsv;
use Exception;

class ImportCSVService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ParseCsv\Csv
     */
    private $csv;

    /**
     * ImportCSVService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->csv = new ParseCsv\Csv();
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

        $report = new ImportCSVReport();

        foreach ($rows as $row) {
            try {
                $report->increaseProcessed();

                [
                    'Product Code' => $productCode,
                    'Product Name' => $productName,
                    'Product Description' => $productDescription,
                    'Stock' => $stock,
                    'Cost in GBP' => $costGBP,
                    'Discontinued' => $discontinued
                ] = $row;
                $productData = new ProductData(
                    $productCode,
                    $productName,
                    $productDescription,
                    (int)$stock,
                    (float)trim($costGBP, '$'), //remove $
                    $discontinued
                );

                if ($testMode) {   //if enabled test mode, we don't import the products.
                    continue;
                }

                if (!$this->entityManager->isOpen()) {  //it's necessary for Symfony 2:  https://stackoverflow.com/questions/14258591/the-entitymanager-is-closed
                    $this->entityManager = $this->entityManager->create(
                        $this->entityManager->getConnection(),
                        $this->entityManager->getConfiguration()
                    );
                }

                $this->entityManager->persist($productData);
                $this->entityManager->flush();
            } catch (Exception $e) {
                $product = implode($this->csv->delimiter, $row);
                $report->addError($e->getMessage(), $product);
            }
        }
        return $report;
    }
}