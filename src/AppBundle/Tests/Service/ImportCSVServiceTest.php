<?php declare(strict_types=1);

namespace AppBundle\Tests\Service;

use AppBundle\Service\CSV\ImportCSVReport;
use AppBundle\Service\CSV\ImportCSVService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImportCSVServiceTest extends WebTestCase
{
    /**
     * @var ImportCSVService
     */
    private $importSCVService;

    public function setUp(): void
    {
        $client = self::createClient();
        $this->importSCVService = $client->getContainer()->get('app.import.csv');
        parent::setUp();
    }

    public function testImport()
    {
        /** @var ImportCSVReport $importCSVReport */
        $path = __DIR__ . '/stock.csv';
        $importCSVReport = $this->importSCVService->import($path, true);

        $processed = $importCSVReport->getProcessed();
        $successful = $importCSVReport->getSuccessful();
        $skipped = $importCSVReport->getCountErrors();

        $this->assertEquals(29, $processed);
        $this->assertEquals(25, $successful);
        $this->assertEquals(4, $skipped);
    }
}