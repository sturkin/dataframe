<?php

namespace Zealot\DataFrame\Interfaces;

interface Column
{
    public function name();
    public function count();
    public function add($value);
    public function get($index);
}