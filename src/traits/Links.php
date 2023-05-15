<?php

namespace YQService\oem\traits;

use YQService\oem\Response\Link;

trait Links
{
    /**
     * @var Link[]
     */
    public $links = [];

    public function getLink(string $action): ?Link
    {
        return array_key_exists($action, $this->links) ? $this->links[$action] : null;
    }
}