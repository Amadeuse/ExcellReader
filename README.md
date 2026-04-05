# ExcellReader

Simple PHP Excel reader built with `openspout/openspout`.

It reads files from the `upload/` folder and returns the content as an **array** or **JSON**.

---

## ✨ Features

- Supports `xlsx`, `csv`, and `ods`
- Uses Composer autoload
- Treats the first row as headers
- Returns data grouped by sheet name
- Includes a simple runnable example

---

## 📋 Requirements

- PHP `8.2+`
- Composer

---

## 📦 Installation

```bash
composer install
```

---

## 🚀 Usage

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Excell\Reader\ExcelReader;

$reader = new ExcelReader(__DIR__ . '/upload');
$data = $reader->read('account_statement.xlsx');

print_r($data);
```

### Read first available file from `upload/`

```php
$data = $reader->readFirstAvailableFile();
```

---

## ▶️ Quick Test

Put an Excel file into `upload/` and run:

```bash
php read_upload_excel.php
```

---

## 📌 Example Output

```json
{
  "Summary": [
    {
      "Account Statement:": "Account Type:",
      "column_3": "..."
    }
  ]
}
```

---

## 📁 Project Structure

```text
src/
  Reader/
    ExcelReader.php
read_upload_excel.php
upload/
composer.json
composer.lock
```

---

## ℹ️ Notes

- `upload/` is kept in the repository with `.gitkeep`
- Real uploaded files are ignored by `.gitignore`
- `vendor/` is not committed to Git

---

## 🔗 Repository

GitHub: [Amadeuse/ExcellReader](https://github.com/Amadeuse/ExcellReader)
