<?php

declare(strict_types=1);

namespace App;

use RuntimeException;

class CsvReader
{
    /**
     * @param string $filePath
     * @param string $delimiter
     * @param string $enclosure
     *
     * @return array
     *
     * @throws RuntimeException
     */
    public static function read(string $filePath, string $delimiter = ',', string $enclosure = '"'): array
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new RuntimeException('CSV file not found or unreadable.');
        }

        $rows = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 0, $delimiter, $enclosure)) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }

        return $rows;
    }
}
