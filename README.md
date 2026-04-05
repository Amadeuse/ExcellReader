# ExcellReader

`ExcellReader` is a small PHP project that reads Excel files from the `upload/` folder using `openspout/openspout` and returns the data as an array or JSON.

## Features

- Read `xlsx`, `csv`, and `ods` files
- Automatically load classes through Composer
- Use the first row as headers
- Return data grouped by sheet name
- Simple example endpoint for quick testing

## Requirements

- PHP `8.2+`
- Composer

## Installation

```bash
composer install
```

## Usage in PHP

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Excell\Reader\ExcelReader;

$reader = new ExcelReader(__DIR__ . '/upload');
$data = $reader->read('account_statement.xlsx');

print_r($data);
```

## Quick test

Run:

```bash
php read_upload_excel.php
```

## Project structure

```text
src/Reader/ExcelReader.php   Main Excel reader class
read_upload_excel.php        Example runner
upload/                      Input files folder
composer.json                Composer config
```

## Notes

- The `upload/` folder is kept in the repository with `.gitkeep`
- Uploaded Excel files themselves are ignored by `.gitignore`
- Dependencies are installed with Composer and are not committed to Git

## Repository

GitHub: `https://github.com/Amadeuse/ExcellReader`
