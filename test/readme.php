<?php

use Zealot\DataFrame\IOUtils;
use Zealot\Filesystem\Csv;

$tmpDir = '/tmp';

$files_data = [
    [
        'type' => 'file',
        'name' => 'csvfile_emails.csv',
        'data' => [
            ['email', 'name'],
            ['sturkin30@gmail.com', 'sergey'],
            ['sturkin@gmail.com', ''],
        ],
    ],
];

function createFiles($files_data, $tmpDir)
{
    foreach ($files_data as $elem) {
        $fileName = $tmpDir.DIRECTORY_SEPARATOR.$elem['name'];
        if ($elem['type'] == 'file') {
            $writer = new Csv\Writer($fileName, 'w+');
            foreach ($elem['data'] as $line) {
                $writer->addRow($line);
            }
        } elseif ($elem['type'] == 'dir') {
            mkdir($fileName);
        }
    }
}

function deleteFiles($files_data, $tmpDir)
{
    foreach ($files_data as $elem) {
        $fileName = $tmpDir.DIRECTORY_SEPARATOR.$elem['name'];
        unlink($fileName);
    }
}

createFiles($files_data, $tmpDir);
/**** START ****/
//Lib to work with csv files/assoc arrays like DB table. For now implemented only whereIn.

//Usage:

$pathToCsvFile = '/tmp/csvfile_emails.csv';

$utils = new IOUtils();
$dataFrame = $utils->fromCsvFile($pathToCsvFile);
$filteredDF = $dataFrame->filter()->whereIn('email', ['sturkin30@gmail.com'])->get();

foreach ($filteredDF->getAssocArrayIterator() as $line) {
    var_dump($line);
}

//$utils->toCsvFile($filteredDF,'path to file')

/*** END ***/
deleteFiles($files_data, $tmpDir);
