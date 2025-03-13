<?php

declare(strict_types=1);

namespace App\Command;

use App\CsvReader;
use RuntimeException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CsvRendererCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('render')
            ->setDescription('Renders a CSV file as a table')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to the CSV file')
            ->addOption('delimiter', 'd', InputOption::VALUE_OPTIONAL, 'CSV delimiter', ',')
            ->addOption('enclosure', 'e', InputOption::VALUE_OPTIONAL, 'CSV enclosure', '"');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int Exit code: 0 for success, 1 for error.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $input->getArgument('file');
        $delimiter = $input->getOption('delimiter');
        $enclosure = $input->getOption('enclosure');

        try {
            $csvData = CsvReader::read($filePath, $delimiter, $enclosure);
        } catch (RuntimeException $ex) {
            $output->writeln(sprintf('<error>%s</error>', $ex->getMessage()));
            return 1;
        }

        if (empty($csvData) || count($csvData) < 2 || count($csvData[0]) < 2) {
            $output->writeln('<error>CSV must have at least 2 columns and 2 rows.</error>');
            return 1;
        }

        $table = new Table($output);
        $table->setHeaders($csvData[0])
            ->setRows(array_slice($csvData, 1))
            ->render();

        return 0;
    }
}
