<?php

namespace YQService\oem\Response;

class FieldSelect extends Field
{
    /**
     * @var boolean
     */
    public $multiple;

    /**
     * @var Option[]
     */
    public $options = [];

}