<?php

namespace YQService\oem\Response;

class FieldInput extends Field
{
    /**
     * @var string
     */
    public $pattern;

    /**
     * @var Example[]
     */
    public $examples = [];
}