<?php
declare(strict_types=1);

namespace Zealot\DataFrame;


use Zealot\Filesystem\Exception\Exception;

class DataFrameContainer
{
    private $fieldsNames = [];
    private $fieldsNamesInverted = [];

    //TODO: refactor to fieldObjects
    private $fieldsData = [];

    public function __construct(array $fieldNames)
    {
        $this->setFieldsNames($fieldNames);
    }

    // START GETTERS/SETTERS //
    public function getFieldsNames(): array {
        return $this->fieldsNames;
    }

    protected function setFieldsNames(array $names) {
        $this->fieldsNames = $names;
        $this->fieldsNamesInverted = array_flip($names);
    }

    protected function getFieldsData(): array
    {
        return $this->fieldsData;
    }
    protected function setFieldsData(array $fieldsData)
    {
        $this->fieldsData = $fieldsData;
    }

    protected function getFieldsNamesInverted(): array
    {
        return $this->fieldsNamesInverted;
    }
    protected function setFieldsNamesInverted(array $fieldsNamesInverted)
    {
        $this->fieldsNamesInverted = $fieldsNamesInverted;
    }

    // END GETTERS/SETTERS //


    public function addLine(array $line) {
        $fieldsData = $this->getFieldsData();
        $this->validateLine($line);
        foreach ($line as $name => $value) {
            $id = $this->fieldNameToId($name);
            $fieldsData[$id][] = $value;
        }

        $this->setFieldsData($fieldsData);
    }

    public function getFieldData($name) {
        $id = $this->fieldNameToId($name);
        $data = $this->getFieldsData();

        return $data[$id];
    }

    public function __toArray(array $index = []) {
        $data = [];

        $fieldNames = $this->getFieldsNames();
        $indexArr = (!empty($index)) ? $index : $this->getIndexArr();
        foreach ($indexArr as $index) {
            $row = [];
            foreach ($fieldNames as $name) {
                $row[$name] = $this->getValue($name,$index);
            }
            $data[] = $row;
        }

        return $data;
    }

    protected function getValue($name, $index) {
        $id = $this->fieldNameToId($name);
        $fieldsData = $this->getFieldsData();

        return $fieldsData[$id][$index];
    }

    protected function getIndexArr() {
        $fields = $this->getFieldsNames();
        $indexArr = array_keys($this->getFieldData(array_shift($fields)));

        return $indexArr;
    }

    protected function fieldNameToId($fieldName) {
        $fieldsNamesInverted = $this->getFieldsNamesInverted();
        if( !array_key_exists($fieldName,$fieldsNamesInverted) ) {
            throw new \Exception('This fieldName doesn\'t exists in dataBase');
        }

        $id = $this->fieldsNamesInverted[$fieldName];
        return $id;
    }

    protected function validateFieldValue($value) {

    }

    protected function validateLine($line) {
        $fieldNames = $this->getFieldsNames();
        if( count($fieldNames) !== count($line) ){
            throw new \Exception('Invalid Line Length');
        }
    }


}