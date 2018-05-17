<?php
/**
 * Created by PhpStorm.
 * User: sturkin30
 * Date: 09.04.18
 * Time: 15:15
 */

namespace Zealot\DataFrame\Column;

use Zealot\DataFrame\Interfaces\Column;

class Iterator implements \SeekableIterator
{
    private $column = null;
    private $position = 0;
    private $maxPosition = 0;

    public function __construct(Column $column)
    {
        $this->setColumn($column);
        $this->setMaxPosition($column->count()-1);
    }

    // SeekableIterator
    public function seek($position) {
        $this->setPosition($position);
    }

    public function rewind() {
        $this->setPosition(0);
    }

    public function current() {
        return $this->getColumn()->get($this->getPosition());
    }

    public function key() {
        return $this->getPosition();
    }

    public function next() {
        $this->incrementPosition();
    }

    public function valid() {
        return ($this->getPosition() <= $this->getMaxPosition());
    }
    // END SeekableIterator

    // START GETTERS/SETTERS //
    protected function getColumn(): Column
    {
        return $this->column;
    }
    protected function setColumn(Column $column)
    {
        $this->column = $column;
    }

    protected function getPosition(): int
    {
        return $this->position;
    }
    protected function setPosition(int $position)
    {
        $this->position = $position;
    }
    protected function incrementPosition() {
        ++$this->position;
    }

    protected function getMaxPosition(): int
    {
        return $this->maxPosition;
    }
    protected function setMaxPosition(int $maxPosition)
    {
        $this->maxPosition = $maxPosition;
    }
    // END GETTERS/SETTERS //
}