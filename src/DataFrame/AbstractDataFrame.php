<?php
declare(strict_types=1);

namespace Zealot\DataFrame\DataFrame;

use Zealot\DataFrame\Exception;
use Zealot\DataFrame\Iterator;
use Zealot\DataFrame\Filter;
use Zealot\DataFrame\Column;
use Zealot\DataFrame\Interfaces;


abstract class AbstractDataFrame implements Interfaces\DataFrame
{
    private $columns = [];
    private $columnNames = [];
    private $columnNamesFlipped = [];
    private $size = 0;

    public function __construct(array $columnNames)
    {
        $this->initColumns($columnNames);
    }

    public function count() {
        return $this->getSize();
    }

    public function addLine($line) {
        $this->validateLine($line);
        foreach ($line as $name => $value) {
            $this->addColumnValue($name,$value);
        }
        $this->incrementSize();
    }

    public function getLine(int $offset, $fields = []) {
        $columnNames = (!empty($fields)) ? $fields : $this->getColumnNames();
        $line = [];
        foreach ($columnNames as $name) {
            $line[$name] = $this->getColumnValue($name, $offset);
        }

        return $line;
    }

    public function getColumnIterator($name) {
        $id = $this->columnNameToId($name);
        $columns = $this->getColumns();

        return new Column\Iterator($columns[$id]);
    }

    public function getAssocArrayIterator() {
        return Iterator::createTypeAssocIterator($this);
    }

    public function getCsvArrayIterator() {
        return Iterator::createTypeCsvIterator($this);
    }

    public function filter(): Filter {
        return new Filter($this);
    }

    public function createCopyByIndex($indexArr = [], $fields = []): Interfaces\DataFrame {
        if(!is_array($indexArr) && !($indexArr instanceof \Iterator)) {
            throw new Exception('invalid index');
        }
        if(empty($indexArr)) {
            $indexArr = range(0,$this->count()-1, 1);
        }
        $columnNames = !empty($fields) ? $fields : $this->getColumnNames();
        $newDf = new static($columnNames);
        foreach ($indexArr as $index) {
            $line = $this->getLine($index,$fields);
            $newDf->addLine($line);
        }

        return $newDf;
    }

    abstract protected function createColumn($name);

    protected function initColumns(array $columnNames) {
        foreach ($columnNames as $name) {
            $this->addColumn($this->createColumn($name));
        }
    }


    protected function addColumn(Interfaces\Column $column) {
        $name = $column->name();
        $this->columns[] = $column;
        $this->addColumnName($name);
    }

    protected function addColumnValue($name, $value) {
        $id = $this->columnNameToId($name);
        $columns = $this->getColumns();
        $columns[$id]->add($value);
    }

    protected function getColumnValue($name, $offset) {
        $id = $this->columnNameToId($name);
        $columns = $this->getColumns();
        return $columns[$id]->get($offset);
    }


    protected function addColumnName($name) {
        $this->columnNames[] = $name;
        $this->columnNamesFlipped = array_flip($this->columnNames);
    }

    protected function columnNameToId($name) {
        $flippedNames = $this->getColumnNamesFlipped();
        if( !array_key_exists($name,$flippedNames) ) {
            throw new \Exception('This column name doesn\'t exists');
        }

        $id = $flippedNames[$name];
        return $id;
    }


    protected function validateLine($line) {
        if( !is_array($line) && !($line instanceof \Traversable) && !($line instanceof \Countable) ) {
            throw new \Exception('Invalid line to add');
        }
        if (count($line) <= 0) {
            throw new \Exception('Empty line');
        }
        if (!empty($this->columns) && count($this->columns) !== count($line)) {
            throw new \Exception('Invalid line size');
        }

    }


    // START GETTERS/SETTERS //
    protected function getColumns(): array
    {
        return $this->columns;
    }
    protected function setColumns(array $columns)
    {
        $this->columns = $columns;
    }

    protected function getColumnNames(): array
    {
        return $this->columnNames;
    }
    protected function setColumnNames(array $columnNames)
    {
        $this->columnNames = $columnNames;
    }

    protected function getColumnNamesFlipped(): array
    {
        return $this->columnNamesFlipped;
    }

    protected function getSize(): int
    {
        return $this->size;
    }
    protected function setSize(int $size)
    {
        $this->size = $size;
    }

    protected function incrementSize() {
        $this->size++;
    }
    // END GETTERS/SETTERS //

}