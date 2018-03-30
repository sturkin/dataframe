<?php
declare(strict_types=1);

namespace Zealot\DataFrame;

use Zealot\DataFrame\DataFrameContainer;

class AbstractDataFrame
{

    private $container = null;

    // START GETTERS/SETTERS //
    public function getContainer(): DataFrameContainer
    {
        return $this->container;
    }
    public function setContainer(DataFrameContainer $fields)
    {
        $this->container = $fields;
    }
    public function isContainerInited() {
        return !empty($this->container);
    }
    // END GETTERS/SETTERS //


    public function __toCsvArray() {
        $container = $this->getContainer();
        $header = $container->getFieldsNames();
        $data = $container->__toArray();

        $csvArr = [];
        $csvArr[] = $header;
        foreach ($data as $row) {
            $csvArr[] = array_values($row);
        }

        return $csvArr;
    }

    protected function addDataFromArray(array $data) {
        foreach ($data as $line) {
            $this->addLine($line);
        }
    }

    protected function addLine(array $line) {

        $this->initContainerFromLineIfNeeded($line);
        $dataBase = $this->getContainer();
        $dataBase->addLine($line);
    }

    protected function initContainerFromLineIfNeeded(array $line) {
        if( !$this->isContainerInited() ) {
            $fields = array_keys($line);
            $container = new DataFrameContainer($fields);
            $this->setContainer($container);
        }
    }

}