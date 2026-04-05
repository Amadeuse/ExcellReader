<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Excell\Reader\ExcelReader;

try {
    $reader = new ExcelReader(__DIR__ . '/upload');

    // კონკრეტული ფაილისთვის გამოიყენე: $reader->read('account_statement.xlsx');
    $data = $reader->readFirstAvailableFile();

    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
