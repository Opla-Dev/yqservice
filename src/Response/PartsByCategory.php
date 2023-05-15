<?php

namespace YQService\oem\Response;

class PartsByCategory
{
    /**
     * @var CategoryShort
     */
    public $category;

    /**
     * @var PartsByUnit[]
     */
    public $units = [];
}