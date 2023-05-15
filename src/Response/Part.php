<?php

namespace YQService\oem\Response;

use YQService\oem\OEMService;
use YQService\oem\traits\CurrentFilterState;
use YQService\oem\traits\Links;

class Part
{
    use CurrentFilterState;
    use Links;

    /**
     * @var MeasuredValue
     */
    public $qty;

    /**
     * @var string
     */
    public $partNumberFormatted;

    /**
     * @var string
     */
    public $partNumber;

    /**
     * @var string
     */
    public $partName;

    /**
     * @var string
     */
    public $displayName;

    /**
     * @var AttrNode[]
     */
    public $attributes = [];

    /**
     * @var string
     */
    public $areaCode;

    /**
     * @var boolean
     */
    public $matched;

    /**
     * @var Link[]
     */
    public $refs = [];

    /**
     * @var PartSection[]
     */
    public $related = [];

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