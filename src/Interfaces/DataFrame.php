<?php
/**
 * Created by PhpStorm.
 * User: sturkin30
 * Date: 09.04.18
 * Time: 17:45.
 */

namespace Zealot\DataFrame\Interfaces;

use Zealot\DataFrame\Filter;

interface DataFrame
{
    //TODO: use type-hint Iterables instead array when update class to 7.1
    public function addLine($line);

    public function getLine(int $offset);

    public function count();

    public function filter(): Filter;

    public function getColumnIterator($name);

    public function getAssocArrayIterator();

    public function getCsvArrayIterator();

    public function createCopyByIndex($indexArr, $fields): self;
}
