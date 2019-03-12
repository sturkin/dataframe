<?php
/**
 * Created by PhpStorm.
 * User: sturkin30
 * Date: 24.03.18
 * Time: 22:15.
 */

namespace Zealot\DataFrame;

class Filter
{
    private $dataFrame = null;
    private $where = [];

    public function __construct($container)
    {
        $this->setDataFrame($container);
    }

    public function whereIn(string $fieldName, $in)
    {
        $this->addWhere([$fieldName, 'in', $in]);

        return $this;
    }

    public function get(array $fields = []): Interfaces\DataFrame
    {
        $result = $this->getDataFrame()->createCopyByIndex($this->getResultIndexes(), $fields);

        return $result;
    }

    protected function getResultIndexes()
    {
        $dataFrame = $this->getDataFrame();
        $whereStatements = $this->getWhere();
        $index = [];
        foreach ($whereStatements as $statement) {
            list($fieldName, $filter, $value) = $statement;
            if ($filter == 'in') {
                $field = $dataFrame->getColumnIterator($fieldName);
                $index = $this->doFilterInFiltering($field, $value, $index);
            }
        }

        return $index;
    }

    protected function doFilterInFiltering($field, $in, array $validIndexes = [])
    {
        $resultIndexes = [];
        foreach ($field as $index => $value) {
            if (!empty($validIndexes) && !in_array($index, $validIndexes)) {
                continue;
            }
            if (in_array($value, $in)) {
                $resultIndexes[] = $index;
            }
        }

        return $resultIndexes;
    }

    // START GETTER/SETTER //
    protected function getDataFrame(): Interfaces\DataFrame
    {
        return $this->dataFrame;
    }

    protected function setDataFrame(Interfaces\DataFrame $dataFrame)
    {
        $this->dataFrame = $dataFrame;
    }

    protected function getWhere(): array
    {
        return $this->where;
    }

    protected function setWhere(array $where)
    {
        $this->where = $where;
    }

    protected function addWhere(array $where)
    {
        $this->where[] = $where;
    }

    // END GETTER/SETTER //
}
