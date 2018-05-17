<?php

namespace Zealot\DataFrame\DataFrame;

use Zealot\DataFrame\Column;

class TmpTable extends AbstractDataFrame
{
    private $dir = '/tmp/test/';

    protected function createColumn($name)
    {
        $col = new Column\TmpTable($name,$this->dir);
        return $col;
    }

    public function getColumnIterator($name) {
        $id = $this->columnNameToId($name);
        $columns = $this->getColumns();

        return new Column\Iterator($columns[$id]);
    }

}