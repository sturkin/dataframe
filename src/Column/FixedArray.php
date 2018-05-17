<?php

namespace Zealot\DataFrame\Column;


class FixedArray extends AbstractColumn
{
    private $array = null;
    private $position = 0;
    private $curentSize = 0;
    private $maxSize = 30;

    public function __construct($name)
    {
        parent::__construct($name);
        $this->array = new \SplFixedArray($this->maxSize);

    }

    public function count() {
        return $this->getCurentSize();
    }
    public function add($value) {
        $this->checkSize();
        $this->array[$this->position] = $value;
        $this->position++;
        $this->incrementCurrentSize();
    }
    public function get($index) {
        return $this->array[$index];
    }

    protected function checkSize() {
        if ($this->curentSize == $this->maxSize) {
            $newSize = $this->maxSize*2+1;
            $this->array->setSize($newSize);
            $this->maxSize = $newSize;
        }
    }

    protected function incrementCurrentSize() {
        $this->curentSize++;
    }

    // START GETTERS/SETTERS //
    protected function getCurentSize(): int
    {
        return $this->curentSize;
    }
    protected function setCurentSize(int $curentSize)
    {
        $this->curentSize = $curentSize;
    }

    protected function getMaxSize(): int
    {
        return $this->maxSize;
    }
    protected function setMaxSize(int $maxSize)
    {
        $this->maxSize = $maxSize;
    }

    protected function getArray()
    {
        return $this->array;
    }
    protected function setArray(\SplFixedArray $array)
    {
        $this->array = $array;
    }

    public function getPosition(): int
    {
        return $this->position;
    }
    public function setPosition(int $position)
    {
        $this->position = $position;
    }
    // END GETTERS/SETTERS //

}