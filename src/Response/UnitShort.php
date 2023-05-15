<?php

namespace YQService\oem\Response;

use YQService\oem\OEMService;
use YQService\oem\Request\FilterDataRequest;
use YQService\oem\traits\CurrentFilterState;
use YQService\oem\traits\Links;

class UnitShort
{
    use Links;
    use CurrentFilterState;

    public const IMAGE_SIZE_250 = '250';
    public const IMAGE_SIZE_225 = '225';
    public const IMAGE_SIZE_200 = '200';
    public const IMAGE_SIZE_175 = '175';
    public const IMAGE_SIZE_150 = '150';
    public const IMAGE_SIZE_FULL = 'source';

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
     * @var string[]
     */
    public $imageNames;

    /**
     * @var AttrNode[]
     */
    public $attributes = [];

    public function getUnitInfo(): Unit
    {
        return OEMService::getService()->getUnitInfo($this->getFilterDataRequest());
    }

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

    /**
     * @param string $size IMAGE_SIZE_* constants
     * @return string[]
     */
    public function getImageNames(string $size = ''): array
    {
        $result = [];

        foreach ($this->imageNames as $url) {
            $result[] = str_replace('%size%', $size, $url);
        }

        return $result;
    }
}