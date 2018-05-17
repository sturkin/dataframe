<?php
/**
 * Created by PhpStorm.
 * User: sturkin30
 * Date: 14.04.18
 * Time: 21:46
 */

namespace Zealot\DataFrame\Column;

use Zealot\DataFrame\Exception;
use Zealot\DataFrame\Interfaces\Column;
use Zealot\Filesystem\File;


//TODO: move read/write buffes to separate objs
class TmpTable extends AbstractColumn
{
    CONST LINE_BREAK_ESCAPE_CHARS = "#!\\n@!#";
    CONST BUFFER_SIZE = 1024*1024*64;
    CONST READ_BUFFER_SIZE = 1024*1024;

    private $dir = null;
    private $fileObj = null;
    private $size = 0;

    private $buffer = '';
    private $bufferPosition = 0;

    private $readBuffer = [];
    private $readBufferStartPosition = 0;
    private $readBufferLength = 0;



    //TODO: change $dir from str to some obj
    public function __construct($name, $dir = '/tmp/')
    {
        parent::__construct($name);
        $this->setDir($dir);
        $this->initFileObj();
    }

    public function count() {
        return $this->getSize();
    }
    public function add($value) {
        //$value = str_replace(PHP_EOL, static::LINE_BREAK_ESCAPE_CHARS, $value);
        $this->bufferAdd($value . PHP_EOL);
        $this->incrementSize();
    }
    public function get($index) {
        $this->flushBuffer();

        $value = $this->getFromReadBuffer($index);
        $value = str_replace(static::LINE_BREAK_ESCAPE_CHARS, PHP_EOL, $value);

        return $value;
    }

    public function __destruct()
    {
        $this->destroyFileObj();
    }

    // read buffer start
    protected function getFromReadBuffer($index) {
        if(!$this->isIndexExistsInReadBuffer($index)) {
            $this->reFillReadBuffer($index);
        }
        $buffer = $this->getReadBuffer();
        return $buffer[$index-$this->getReadBufferStartPosition()];
    }

    protected function reFillReadBuffer($index) {

        $file = $this->getFileObj();
        $file->seek($index);
        $str = $file->read(static::READ_BUFFER_SIZE);
        $buffer = explode(PHP_EOL, $str);
        $bufferCount = 10000;
        if (!$file->isEnd()) {
            $buffer = array_slice($buffer,0,$bufferCount);
        }

        $this->setReadBuffer($buffer);
        $this->setReadBufferLength($bufferCount);
        $this->setReadBufferStartPosition($index);
        unset($buffer);

    }

    protected function isIndexExistsInReadBuffer($index) {
        $res = false;
        $buffer = $this->getReadBuffer();
        $minBufferIndex = $this->getReadBufferStartPosition();
        $maxBufferIndex = $minBufferIndex + $this->getReadBufferLength();
        if (!empty($buffer) && $index <= $maxBufferIndex && $index >= $minBufferIndex) {
            $res = true;
        }

        return $res;
    }
    // read buffer start



    // write buffer start
    protected function bufferAdd($value) {
        $this->buffer .= $value;
        $this->checkBuffer();
    }

    protected function checkBuffer() {
        if (($this->getSize()-$this->getBufferPosition()) > 100 && strlen($this->getBuffer()) >= static::BUFFER_SIZE) {
            $this->flushBuffer();
        }
    }

    public function flushBuffer() {
        $buffer = $this->getBuffer();
        if (!empty($buffer)) {
            $file = $this->getFileObj();
            $file->seek($this->getBufferPosition());
            $file->write($this->getBuffer());

            $buffer = '';
            $this->setBufferPosition($this->getSize());
            $this->setBuffer($buffer);
        }
    }
    // write buffer end


    protected function initFileObj() {
        $name = $this->getName();
        $dir = $this->getDir();
        $tmpFilePath = $dir . $name . '_' . time() . rand(1,100) . microtime() . '.csv';

        $this->setFileObj(new File($tmpFilePath,'w+'));
    }

    protected function destroyFileObj() {
        $filePath = $this->fileObj->getRealPath();
        $this->fileObj = null;
        unlink($filePath);
    }

    // START GETTERS/SETTERS //
    protected function getDir()
    {
        return $this->dir;
    }
    //TODO: add dir slashes normalization
    protected function setDir($dir)
    {
        if(!file_exists($dir)) {
            throw new Exception('dir is not exists');
        }
        $this->dir = $dir;
    }

    protected function getFileObj(): File
    {
        return $this->fileObj;
    }
    protected function setFileObj(File $fileObj)
    {
        $this->fileObj = $fileObj;
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

    protected function getBuffer(): string
    {
        return $this->buffer;
    }
    protected function setBuffer(string $buffer)
    {
        $this->buffer = $buffer;
    }

    protected function getBufferPosition(): int
    {
        return $this->bufferPosition;
    }
    protected function setBufferPosition(int $bufferPosition)
    {
        $this->bufferPosition = $bufferPosition;
    }

    public function getReadBuffer(): array
    {
        return $this->readBuffer;
    }
    public function setReadBuffer(array $readBuffer)
    {
        $this->readBuffer = $readBuffer;
    }

    public function getReadBufferStartPosition(): int
    {
        return $this->readBufferStartPosition;
    }
    public function setReadBufferStartPosition(int $readBufferStartPosition)
    {
        $this->readBufferStartPosition = $readBufferStartPosition;
    }

    public function getReadBufferLength(): int
    {
        return $this->readBufferLength;
    }
    public function setReadBufferLength(int $readBufferLength)
    {
        $this->readBufferLength = $readBufferLength;
    }
    // END GETTERS/SETTERS //

}