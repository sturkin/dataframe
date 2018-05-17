<?php
declare(strict_types=1);

namespace Zealot\DataFrame;

use Zealot\DataFrame\Interfaces;


class Iterator implements \SeekableIterator
{
    CONST TYPE_ASSOC = 10;
    CONST TYPE_CSV = 20;

    private $type = null;
    private $dataFrame = null;
    private $position = 0;
    private $maxPosition = 0;

    public static function createTypeCsvIterator(Interfaces\DataFrame $dataFrame) {
        return new static($dataFrame, static::TYPE_CSV);
    }

    public static function createTypeAssocIterator(Interfaces\DataFrame $dataFrame) {
        return new static($dataFrame, static::TYPE_ASSOC);
    }

    public function __construct(Interfaces\DataFrame $dataFrame, int $type)
    {
        if (!$this->isTypeValid($type)) {
            throw new Exception('Invalid iterator type');
        }
        $this->setType($type);
        $this->setDataFrame($dataFrame);
        $this->setMaxPosition($dataFrame->count()-1);
    }

    // SeekableIterator
    public function seek($position) {
        $this->setPosition($position);
    }

    public function rewind() {
        $this->setPosition(0);
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

    public function current() {
        //return $this->getDataFrame()->get($this->getPosition());
        if ($this->getType() == static::TYPE_ASSOC) {
            return $this->getAssocCurrent();
        } elseif ($this->getType() == static::TYPE_CSV) {
            return $this->getCsvCurrent();
        }
    }
    // END SeekableIterator

    protected function getCsvCurrent() {
        if ($this->getPosition() === 0) {
            $line = $this->getDataFrameLineByPosition(0);
            return array_keys($line);
        } else {
            $line = $this->getDataFrameLineByPosition($this->getPosition()-1);
            return array_values($line);
        }
    }

    protected function getAssocCurrent() {
        return $this->getDataFrameLineByPosition($this->getPosition());
    }

    protected function getDataFrameLineByPosition(int $position) {
        return $this->getDataFrame()->getLine($position);
    }

    protected function isTypeValid($type) {
        return in_array($type, [
            static::TYPE_ASSOC,
            static::TYPE_CSV
        ]);
    }

    // START GETTERS/SETTERS //
    protected function getDataFrame(): Interfaces\DataFrame
    {
        return $this->dataFrame;
    }
    protected function setDataFrame(Interfaces\DataFrame $dataFrame)
    {
        $this->dataFrame = $dataFrame;
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
    //CSV-like array have 1 addition row - header
    protected function setMaxPosition(int $maxPosition)
    {
        if ($this->type == static::TYPE_CSV) $maxPosition += 1;
        $this->maxPosition = $maxPosition;
    }

    protected function getType()
    {
        return $this->type;
    }
    protected function setType($type)
    {
        $this->type = $type;
    }
    // END GETTERS/SETTERS //

}