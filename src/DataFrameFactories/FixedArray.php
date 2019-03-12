<?php
/**
 * Created by PhpStorm.
 * User: sturkin30
 * Date: 11.04.18
 * Time: 18:23.
 */

namespace Zealot\DataFrame\DataFrameFactories;

use Zealot\DataFrame\DataFrame;
use Zealot\DataFrame\Interfaces;

class FixedArray implements Interfaces\DataFrameFactory
{
    public function buildDataFrame(array $columnNames): Interfaces\DataFrame
    {
        return new DataFrame\FixedArray($columnNames);
    }
}
