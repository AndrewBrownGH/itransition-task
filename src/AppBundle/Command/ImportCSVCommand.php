<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class ImportCSVCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('import:csv');
        $this->setDescription('Import a CSV file');
        $this->setHelp('This command allows you to import a CSV file');

        $this->addArgument('path', InputArgument::REQUIRED, 'The path of the CSV file');
        $this->addOption('test', null, InputOption::VALUE_NONE, 'You can validate your CSV file without import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outputStyle = new OutputFormatterStyle('red', 'yellow', array('bold', 'blink'));
        $output->getFormatter()->setStyle('fire', $outputStyle);

        $importSCVService = $this->getContainer()->get('app.import.csv');
        $report = $importSCVService->import($input->getArgument('path'), $input->getOption('test'));
        $errors = $report->getErrors();

        if ($input->getOption('test')) {
            $output->writeln('<question>you enabled the test mode: the products are not imported</question>');
        }

        $output->writeln('processed items: ' . '<info>' . $report->getProcessed() . '</info>');
        $output->writeln('successful items: ' . '<info>' . $report->getSuccessful() . '</info>');
        $output->writeln('skipped items: ' . '<info>' . $report->getCountErrors() . '</info>');

        foreach ($errors as $error) {
            $output->writeln('<comment>' . $error['message'] . '</comment>');
            $output->writeln('<fire>' . $error['product'] . '</fire>' . PHP_EOL);
        }
    }
}
