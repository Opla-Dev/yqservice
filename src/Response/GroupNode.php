<?php

namespace YQService\oem\Response;

use YQService\oem\OEMService;
use YQService\oem\traits\CurrentFilterState;
use YQService\oem\traits\Links;

class GroupNode
{
    use CurrentFilterState;
    use Links;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $synonyms;

    /**
     * @var string
     */
    public $contains;

    /**
     * @var GroupNode[]
     */
    public $children = [];

    public function getGroupParts(): PartsListByCategory
    {
        return OEMService::getService()->getGroupParts($this->getFilterDataRequest());
    }

    public function getGroupPartsAll(): PartsListByCategory
    {
        return OEMService::getService()->getGroupPartsAll($this->getFilterDataRequest());
    }

    public function isGetGroupPartsAvailable(): bool
    {
        return $this->getLink('getGroupParts') != null;
    }

    public function isGetGroupPartsAllAvailable(): bool
    {
        return $this->getLink('getGroupPartsAll') != null;
    }
}