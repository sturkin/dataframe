<?php
/**
 * Created by PhpStorm.
 * User: sturkin30
 * Date: 11.04.18
 * Time: 18:10
 */

namespace Zealot\DataFrame;

use Zealot\DataFrame\Interfaces;
use Zealot\DataFrame\DataFrameFactories;
use Zealot\Filesystem\Csv\Reader;
use Zealot\Filesystem\Csv\Writer;


class IOUtils
{
    private $factory = null;


    public function __construct($factory = null)
    {
        if (empty($factory)) {
            $factory = new DataFrameFactories\FixedArray();
        }
        $this->setFactory($factory);
    }

    public function fromArray(array $array): Interfaces\DataFrame
    {
        if (empty($array) || empty($array[0]) || !is_array($array[0]) ) {
            throw new Exception('Invalid array');
        }
        $columnNames = array_keys($array[0]);
        $dataFrame = $this->getFactory()->buildDataFrame($columnNames);
        foreach ($array as $line) {
            $dataFrame->addLine($line);
        }

        return $dataFrame;
    }

    public function fromCsvFile($file, $limit = 0, $delimiter=',',$enclosure='"',$escape="\\"): Interfaces\DataFrame
    {
        $reader = new Reader($file,$delimiter,$enclosure,$escape);
        $columnNames = $reader->header();
        $dataFrame = $this->getFactory()->buildDataFrame($columnNames);
        $i = 0;
        foreach ($reader as $line) {
            $dataFrame->addLine($line);
            $i++;
            if(!empty($limit) && $i === $limit) break;
        }
        return $dataFrame;
    }

    public function toCsvFile(Interfaces\DataFrame $df, string $path, int $limit = 0, $delimiter=',',$enclosure='"',$escape="\\")
    {
        $writer = new Writer($path,'w+',$delimiter,$enclosure,$escape);
        $iterator = $df->getCsvArrayIterator();
        $i = 0;
        foreach ($iterator as $line) {
            $writer->addRow($line);
            $i++;
            if(!empty($limit) && $i === $limit) break;
        }
    }

    public function toAssocArray(Interfaces\DataFrame $df, int $limit = 0)
    {
        $iterator = $df->getAssocArrayIterator();
        $data = [];
        $i = 0;
        foreach ($iterator as $line) {
            $data[] = $line;
            $i++;
            if(!empty($limit) && $i === $limit) break;
        }

        return $data;
    }

    // START GETTERS/SETTERS //
    protected function getFactory(): Interfaces\DataFrameFactory
    {
        return $this->factory;
    }
    protected function setFactory(Interfaces\DataFrameFactory $factory)
    {
        $this->factory = $factory;
    }
    // END GETTERS/SETTERS //

}