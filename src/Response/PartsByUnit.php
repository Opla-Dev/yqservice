<?php

namespace YQService\oem\Response;

class PartsByUnit
{
    /**
     * @var UnitShort
     */
    public $unit;

    /**
     * @var PartSection[]
     */
    public $partSections = [];
}