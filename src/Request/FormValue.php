<?php

namespace YQService\oem\Request;

class FormValue
{
    public $name;

    public $value;

    /**
     * @param $name
     * @param $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}