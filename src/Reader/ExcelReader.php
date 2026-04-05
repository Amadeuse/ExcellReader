<?php

declare(strict_types=1);

namespace Excell\Reader;

use DateInterval;
use DateTimeInterface;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Reader\Common\Creator\ReaderFactory;
use RuntimeException;

final class ExcelReader
{
    private string $uploadDirectory;

    public function __construct(?string $uploadDirectory = null)
    {
        $this->uploadDirectory = rtrim(
            $uploadDirectory ?? (dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'upload'),
            DIRECTORY_SEPARATOR
        );
    }

    public function read(string $fileName, bool $useHeaderRow = true): array
    {
        $filePath = $this->uploadDirectory . DIRECTORY_SEPARATOR . ltrim($fileName, DIRECTORY_SEPARATOR);

        if (!is_file($filePath)) {
            throw new RuntimeException("ფაილი ვერ მოიძებნა: {$filePath}");
        }

        return $this->readFromPath($filePath, $useHeaderRow);
    }

    public function readFirstAvailableFile(bool $useHeaderRow = true): array
    {
        $files = glob($this->uploadDirectory . DIRECTORY_SEPARATOR . '*.{xlsx,csv,ods}', GLOB_BRACE);

        if ($files === false || $files === []) {
            throw new RuntimeException('upload ფოლდერში მხარდაჭერილი ფაილი ვერ მოიძებნა. გამოიყენე: xlsx, csv ან ods.');
        }

        sort($files);

        return $this->readFromPath($files[0], $useHeaderRow);
    }

    private function readFromPath(string $filePath, bool $useHeaderRow): array
    {
        $reader = ReaderFactory::createFromFile($filePath);
        $result = [];

        $reader->open($filePath);

        try {
            foreach ($reader->getSheetIterator() as $sheet) {
                $headers = [];
                $rows = [];

                foreach ($sheet->getRowIterator() as $row) {
                    $values = $this->normalizeRow($row);

                    if ($this->isEmptyRow($values)) {
                        continue;
                    }

                    if ($useHeaderRow && $headers === []) {
                        $headers = $this->buildHeaders($values);
                        continue;
                    }

                    $rows[] = $useHeaderRow
                        ? $this->combineWithHeaders($headers, $values)
                        : $values;
                }

                $result[$sheet->getName()] = $rows;
            }
        } finally {
            $reader->close();
        }

        return $result;
    }

    private function normalizeRow(Row $row): array
    {
        $values = [];

        foreach ($row->getCells() as $cell) {
            $value = $cell->getValue();

            if ($value instanceof DateTimeInterface) {
                $value = $value->format('Y-m-d H:i:s');
            } elseif ($value instanceof DateInterval) {
                $value = $this->formatInterval($value);
            }

            $values[] = $value;
        }

        return $values;
    }

    private function buildHeaders(array $row): array
    {
        $headers = [];
        $used = [];

        foreach ($row as $index => $value) {
            $header = trim((string) ($value ?? ''));
            $header = $header !== '' ? $header : 'column_' . ($index + 1);

            if (isset($used[$header])) {
                $used[$header]++;
                $header .= '_' . $used[$header];
            } else {
                $used[$header] = 1;
            }

            $headers[] = $header;
        }

        return $headers;
    }

    private function combineWithHeaders(array $headers, array $values): array
    {
        $row = [];
        $total = max(count($headers), count($values));

        for ($i = 0; $i < $total; $i++) {
            $key = $headers[$i] ?? 'column_' . ($i + 1);
            $row[$key] = $values[$i] ?? null;
        }

        return $row;
    }

    private function isEmptyRow(array $values): bool
    {
        foreach ($values as $value) {
            if ($value !== null && $value !== '') {
                return false;
            }
        }

        return true;
    }

    private function formatInterval(DateInterval $interval): string
    {
        return $interval->format('%d days %h hours %i minutes %s seconds');
    }
}
