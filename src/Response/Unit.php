<?php

namespace YQService\oem\Response;

use YQService\oem\OEMService;
use YQService\oem\Request\FilterDataRequest;
use YQService\oem\traits\CurrentFilterState;
use YQService\oem\traits\Links;

class Unit
{
    use CurrentFilterState;
    use Links;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var ImageMap[]
     */
    public $imageMaps;

    /**
     * @var AttrNode[]
     */
    public $attributes;

    public function getUnitParts(FilterDataRequest $state = null): PartSectionsList
    {
        return OEMService::getService()->getUnitParts($state ?: $this->getFilterDataRequest());
    }

    public function isFilterExists(): bool
    {
        return $this->getLink('getFilter') != null;
    }

    public function getFilter(): ?FilterForm
    {
        $link = $this->getLink('getFilter');
        return $link ? OEMService::getService()->getFilter($this->getFilterDataRequest($link->token)) : null;
    }

}