<?php

namespace YQService\oem\Response;

use YQService\oem\traits\Links;
use YQService\oem\traits\Token;

class CategoryShort
{
    use Links;
    use Token;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $code;
}