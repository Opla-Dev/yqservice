<?php

namespace YQService\oem\Response;

use YQService\oem\OEMService;
use YQService\oem\traits\CurrentFilterState;
use YQService\oem\traits\Links;

class VehicleShort
{
    use CurrentFilterState;
    use Links;

    /**
     * @var NavigationLink[]
     */
    public $navigationLinks = [];

    /**
     * UNDEFINED, PASSENGER, COMMERCIAL, MOTO
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $brand;

    /**
     * @var string
     */
    public $model;

    /**
     * @var AttrNode[]
     */
    public $attributes = [];

    /**
     * @var Property[]
     */
    public $sysProperties = [];

    public function getNavigationLink(string $code): ?NavigationLink
    {
        return array_key_exists($code, $this->navigationLinks) ? $this->navigationLinks[$code] : null;
    }

    public function getSysPropertyFilterLevel(): string
    {
        $property = $this->getSysProperty('filter_level');
        return $property ?: 'full';
    }

    public function getSysProperty(string $name): ?string
    {
        return array_key_exists($name, $this->sysProperties) ? $this->sysProperties[$name]->value : null;
    }

    public function getDefaultNavigationTree(): CategoryNode
    {
        return OEMService::getService()->getNavigationTree($this->getFilterDataRequest());
    }

    public function getNavigationTree($navigationLinkToken = null): CategoryNode
    {
        return OEMService::getService()->getNavigationTree($this->getFilterDataRequest($navigationLinkToken));
    }

    public function getGroups(): GroupNode
    {
        return OEMService::getService()->getGroups($this->getFilterDataRequest());
    }

    public function isGroupsSupported(): bool
    {
        return $this->getNavigationLink('GROUPS') != null;
    }

    public function getVehicleInfo(): Vehicle
    {
        return OEMService::getService()->getVehicleInfo($this->getFilterDataRequest());
    }

    public function getCatalogShort(): CatalogShort
    {
        return OEMService::getService()->getCatalogShort($this->token);
    }

    public function getAllParts(): PartShortList
    {
        return OEMService::getService()->getAllPartsByFilter($this->getFilterDataRequest());
    }

    public function getPartsByName(string $query): PartShortList
    {
        return OEMService::getService()->getPartsByName($this->getFilterDataRequest(), $query);
    }

    public function getPartApplicability(string $partNumber, bool $includeReplacements = false): PartsListByCategory
    {
        return OEMService::getService()->getPartApplicabilityByFilter($this->getFilterDataRequest(), $partNumber, $includeReplacements);
    }
}