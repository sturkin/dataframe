<?php

namespace Zealot\DataFrame\Interfaces;

use Zealot\DataFrame\Interfaces\DataFrame;

interface DataFrameFactory
{
    public function buildDataFrame(array $columnNames): DataFrame;
}