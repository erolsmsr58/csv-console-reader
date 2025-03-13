<?php

declare(strict_types=1);

namespace Tests\Traits;

trait TempCsvTrait
{
    /**
     * @var string|null
     */
    protected ?string $tempFile = null;

    /**
     * @return void
     */
    protected function tearDownTempFile(): void
    {
        if ($this->tempFile !== null && file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
        $this->tempFile = null;
    }

    /**
     * @param string $content
     * 
     * @return string
     */
    protected function createTempCsv(string $content): string
    {
        $file = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($file, $content);
        $this->tempFile = $file;
        return $file;
    }
}
