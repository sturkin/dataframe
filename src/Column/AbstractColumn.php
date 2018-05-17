<?php
/**
 * Created by PhpStorm.
 * User: sturkin30
 * Date: 05.04.18
 * Time: 18:43
 */

namespace Zealot\DataFrame\Column;

use Zealot\DataFrame\Interfaces\Column;



abstract class AbstractColumn implements Column
{

    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function name() {
        return $this->getName();
    }
    abstract public function count();
    abstract public function add($value);
    abstract public function get($index);

    // START GETTERS/SETTERS //
    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    // END GETTERS/SETTERS //


}