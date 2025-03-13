<?php

declare(strict_types=1);

namespace Tests\App\Command;

use Tests\Traits\TempCsvTrait;
use PHPUnit\Framework\TestCase;
use App\Command\CsvRendererCommand;
use Symfony\Component\Console\Tester\CommandTester;

class CsvRendererCommandTest extends TestCase
{
    use TempCsvTrait;

    protected function tearDown(): void
    {
        $this->tearDownTempFile();
        parent::tearDown();
    }

    public function testNonExistentFile(): void
    {
        $command = new CsvRendererCommand();
        $commandTester = new CommandTester($command);

        $exitCode = $commandTester->execute([
            'file' => '/path/to/nonexistent.csv',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('CSV file not found or unreadable.', $output);
        $this->assertSame(1, $exitCode);
    }

    public function testInvalidCsvStructure(): void
    {
        // Create a temporary CSV file with only one row and one column.
        $this->createTempCsv("header\nvalue");

        $command = new CsvRendererCommand();
        $commandTester = new CommandTester($command);

        $exitCode = $commandTester->execute([
            'file' => $this->tempFile,
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('CSV must have at least 2 columns and 2 rows.', $output);
        $this->assertSame(1, $exitCode);
    }

    public function testValidCsvFile(): void
    {
        $csvContent = "col1,col2\nvalue1,value2\nvalue3,value4";
        $this->createTempCsv($csvContent);

        $command = new CsvRendererCommand();
        $commandTester = new CommandTester($command);

        $exitCode = $commandTester->execute([
            'file' => $this->tempFile,
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('col1', $output);
        $this->assertStringContainsString('col2', $output);
        $this->assertSame(0, $exitCode);
    }
}
