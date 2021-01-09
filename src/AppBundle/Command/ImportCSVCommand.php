<?php

namespace AppBundle\Command;

use AppBundle\Service\CSV\ImportCSVReport;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class ImportCSVCommand extends ContainerAwareCommand
{
    /**
     * @var ImportCSVReport
     */
    private $importCSVReport;

    protected function configure()
    {
        $this->setName('import:csv');
        $this->setDescription('Import a CSV file');
        $this->setHelp('This command allows you to import a CSV file');

        $this->addArgument('path', InputArgument::REQUIRED, 'The path of a CSV file');
        $this->addArgument('delimiter', InputArgument::OPTIONAL, 'You can input a delimiter for CSV file');
        $this->addOption('test', null, InputOption::VALUE_NONE, 'You can validate your CSV file without import');
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $importSCVService = $this->getContainer()->get('app.import.csv');

        $this->importCSVReport = $importSCVService->import(
            $input->getArgument('path'),
            $input->getArgument('delimiter'),
            $input->getOption('test')
        );

        if ($input->getOption('test')) {
            $output->writeln('<question>you enabled the test mode: the products won\'t imported</question>');
        }
        if (!$input->getArgument('delimiter')) {
            $output->writeln('<question>you didn\'t input a delimiter: the delimiter will be auto-detect</question>');
        }

        $this->outputReport($output);

        return null;
    }

    private function outputReport(OutputInterface $output): void
    {
        $outputStyle = new OutputFormatterStyle('red', 'yellow', array('bold', 'blink'));
        $output->getFormatter()->setStyle('fire', $outputStyle);

        $output->writeln('processed items: ' . '<info>' . $this->importCSVReport->getProcessed() . '</info>');
        $output->writeln('successful items: ' . '<info>' . $this->importCSVReport->getSuccessful() . '</info>');
        $output->writeln('skipped items: ' . '<info>' . $this->importCSVReport->getCountErrors() . '</info>');

        $errors = $this->importCSVReport->getErrors();

        foreach ($errors as $error) {
            $output->writeln('<comment>' . $error['message'] . '</comment>');
            $output->writeln('<fire>' . $error['product'] . '</fire>' . PHP_EOL);
        }
    }
}
