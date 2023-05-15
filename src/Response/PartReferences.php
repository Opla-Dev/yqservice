<?php

namespace YQService\oem\Response;

class PartReferences
{
    /**
     * @var string
     */
    public $partNumber;

    /**
     * @var string
     */
    public $partName;

    /**
     * @var Catalog[]
     */
    public $catalogs = [];
}