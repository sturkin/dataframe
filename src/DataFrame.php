<?php
declare(strict_types=1);

namespace Zealot\DataFrame;

use Zealot\DataFrame\AbstractDataFrame;
use Zealot\DataFrame\DataFrameContainer;
use Zealot\DataFrame\DataFrameFilter;


use Zealot\Filesystem\Csv\Reader;
use Zealot\Filesystem\Csv\Writer;



class DataFrame extends AbstractDataFrame
{

    public static function fromArray(array $data) {
        $dp = new static();
        $dp->addDataFromArray($data);

        return $dp;
    }

    public static function fromCsv($path, $limit = 0) {
        $reader = new Reader($path);
        $i = 0;
        $data = [];
        foreach ($reader as $line) {
            if ( !empty($limit) && ($i++ >= $limit))  break;
            $data[] = $line;
        }

        return static::fromArray($data);
    }


    public static function saveAsCsv(string $path, DataFrame $dp) {
        $writer = new Writer($path);
        $content = $dp->__toCsvArray();

        $writer->addRows($content);
    }

    public function getArray() {
        return $this->getContainer()->__toArray();
    }

    public function filter() {
        $filter = new DataFrameFilter($this->getContainer());

        return $filter;
    }






}