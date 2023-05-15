<?php

namespace YQService\oem\Response;

use YQService\oem\traits\Links;

class ImageMapArea
{
    use Links;

    /**
     * @var int
     */
    public $x1;

    /**
     * @var int
     */
    public $x2;

    /**
     * @var int
     */
    public $y1;

    /**
     * @var int
     */
    public $y2;

    /**
     * @var string
     */
    public $areaCode;
}