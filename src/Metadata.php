<?php

namespace YQService\oem;

use ReflectionMethod;

class Metadata
{
    /**
     * @var string[]
     */
    public $fields = [];

    /**
     * @var string
     */
    public $keyField;

    /**
     * @var ReflectionMethod
     */
    public $classFactory;
}