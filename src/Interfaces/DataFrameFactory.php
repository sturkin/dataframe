<?php

namespace Zealot\DataFrame\Interfaces;

interface DataFrameFactory
{
    public function buildDataFrame(array $columnNames): DataFrame;
}
