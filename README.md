# Zealot/dataframe

Lib to work with csv files/assoc arrays like DB table. For now implemented only whereIn(); (yeap, been inspired by pandas)

## Getting Started

```php

$pathToCsvFile = '/tmp/csvfile_emails.csv';

$utils = new IOUtils();
$dataFrame = $utils->fromCsvFile($pathToCsvFile);
$filteredDF = $dataFrame->filter()->whereIn('email', ['sturkin30@gmail.com'])->get();

foreach ($filteredDF->getAssocArrayIterator() as $line) {
    var_dump($line);
}

//$utils->toCsvFile($filteredDF,'path to file')
```

### Installing

```
composer require zealot/dataframe
```