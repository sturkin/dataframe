<?php

declare(strict_types=1);

namespace Zealot\DataFrame\DataFrame;

use Zealot\DataFrame\Column;

class FixedArray extends AbstractDataFrame
{
    public function createColumn($name)
    {
        return new Column\FixedArray($name);
    }
}
