<?php
/**
 * Created by PhpStorm.
 * User: sturkin30
 * Date: 24.03.18
 * Time: 22:15
 */

namespace Zealot\DataFrame;

use Zealot\DataFrame\DataFrameContainer;

class DataFrameFilter
{
    private $container = null;
    private $where = [];

    public function __construct($container)
    {
        $this->setContainer($container);
    }

    // START GETTER/SETTER //
    protected function getContainer(): DataFrameContainer
    {
        return $this->container;
    }
    protected function setContainer(DataFrameContainer $container)
    {
        $this->container = $container;
    }

    protected function getWhere(): array
    {
        return $this->where;
    }
    protected function setWhere(array $where)
    {
        $this->where = $where;
    }
    protected function addWhere(array $where) {
        $this->where[] = $where;
    }
    // END GETTER/SETTER //

    public function whereIn(string $fieldName, array $in) {
        $this->addWhere([$fieldName,'in',$in]);

        return $this;
    }

    public function get(): DataFrame {
        $index = $this->getResultIndexes();
        $container = $this->getContainer();
        $data = $container->__toArray($index);

        return DataFrame::fromArray($data);
    }

    protected function getResultIndexes() {
        $container = $this->getContainer();
        $whereStatements = $this->getWhere();
        $index = [];
        foreach ($whereStatements as $statement) {
            list($fieldName, $filter, $value) = $statement;
            if ($filter == 'in') {
                $field  = $container->getFieldData($fieldName);
                $index = $this->doFilterInFiltering($field,$value,$index);
            }
        }

        return $index;
    }

    protected function doFilterInFiltering(array $field, array $in, array $validIndexes = []) {
        $resultIndexes = [];
        foreach ($field as $index => $value) {
            if( !empty($validIndexes) && !in_array($value,$validIndexes)) continue;
            if (in_array($value,$in)) {
                $resultIndexes[] = $index;
            }
        }
        return $resultIndexes;
    }


}