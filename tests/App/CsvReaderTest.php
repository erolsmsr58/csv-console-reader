<?php

declare(strict_types=1);

namespace Tests\App;

use App\CsvReader;
use RuntimeException;
use Tests\Traits\TempCsvTrait;
use PHPUnit\Framework\TestCase;

class CsvReaderTest extends TestCase
{
    use TempCsvTrait;

    protected function tearDown(): void
    {
        $this->tearDownTempFile();
        parent::tearDown();
    }

    public function testNonExistentFileThrowsException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('CSV file not found or unreadable.');
        CsvReader::read('/path/to/nonexistent.csv');
    }

    public function testValidCsvReturnsExpectedArray(): void
    {
        $csvContent = "col1,col2\nvalue1,value2\nvalue3,value4";
        $tempFile = $this->createTempCsv($csvContent);

        $result = CsvReader::read($tempFile);
        $expected = [
            ['col1', 'col2'],
            ['value1', 'value2'],
            ['value3', 'value4'],
        ];
        $this->assertSame($expected, $result);
    }
}
