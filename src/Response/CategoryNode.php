<?php

namespace YQService\oem\Response;

use YQService\oem\exceptions\BehaviorException;
use YQService\oem\OEMService;
use YQService\oem\Request\FilterDataRequest;
use YQService\oem\traits\CurrentFilterState;
use YQService\oem\traits\Links;

class CategoryNode
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
     * @var CategoryNode[]
     */
    public $children = [];

    public function isGetUnitsAvailable(): bool
    {
        return $this->getLink('getUnits') != null;
    }

    public function getUnits(FilterDataRequest $state = null): ?UnitShortList
    {
        if ($state) {
            return OEMService::getService()->getUnits($state);
        }
        if (!$this->token) {
            throw new BehaviorException('Unable to search parts using this Category');
        }
        return OEMService::getService()->getUnits($this->getFilterDataRequest());
    }
}