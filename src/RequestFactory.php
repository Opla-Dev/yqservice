<?php

namespace YQService\oem;

use YQService\oem\Request\FilterDataRequest;
use YQService\oem\Request\FilterStatefullFormDataRequest;
use YQService\oem\Request\FormDataRequest;
use YQService\oem\Response\CatalogInfo;
use YQService\oem\Response\CatalogList;
use YQService\oem\Response\CatalogShort;
use YQService\oem\Response\CategoryNode;
use YQService\oem\Response\Customer;
use YQService\oem\Response\FilterForm;
use YQService\oem\Response\Form;
use YQService\oem\Response\GroupNode;
use YQService\oem\Response\PartReferencesList;
use YQService\oem\Response\PartSectionsList;
use YQService\oem\Response\PartShortList;
use YQService\oem\Response\PartsListByCategory;
use YQService\oem\Response\Unit;
use YQService\oem\Response\UnitShortList;
use YQService\oem\Response\Vehicle;
use YQService\oem\Response\VehicleList;

class RequestFactory
{
    public static function getFactory()
    {
        static $factory = null;
        if (!$factory) {
            $factory = new RequestFactory();
        }

        return $factory;
    }

    /**
     * Catalogs API
     */

    public function catalogs(): Request
    {
        return $this->createRequest(CatalogList::class, 'POST', 'catalogs');
    }

    /**
     * @param string $method
     * @param string $command
     * @param array $body
     */
    protected function createRequest(string $className, string $method, string $command, array $body = []): Request
    {
        return new Request($className, $method, $command, $body);
    }

    public function getCatalogInfo(string $catalogToken): Request
    {
        return $this->createRequest(CatalogInfo::class, 'POST', 'getCatalogInfo', [
            'token' => $catalogToken
        ]);
    }

    public function getCatalogShort(string $catalogToken): Request
    {
        return $this->createRequest(CatalogShort::class, 'POST', 'getCatalogShort', [
            'token' => $catalogToken
        ]);
    }

    public function findPartReferencesByOem(string $oem): Request
    {
        return $this->findPartReferences((new FormDataRequest())->setFieldValue('PartNumber', $oem));
    }

    public function findPartReferences(FormDataRequest $state): Request
    {
        return $this->createRequest(PartReferencesList::class, 'POST', 'findPartReferences', (array)$state);
    }

    /**
     * Vehicles API
     */

    public function getVehicleInfo(FilterDataRequest $request): Request
    {
        return $this->createRequest(Vehicle::class, 'POST', 'getVehicleInfo', (array)$request);
    }

    public function findVehicleOperation(FormDataRequest $state): Request
    {
        return $this->createRequest(VehicleList::class, 'POST', 'findVehicleOperation', (array)$state);
    }

    public function getOperationForm(FormDataRequest $state): Request
    {
        return $this->createRequest(Form::class, 'POST', 'getOperationForm', (array)$state);
    }

    public function findVehicleByVin(string $string, string $catalogToken = null): Request
    {
        return $this->findVehicle((new FormDataRequest($catalogToken))->setFieldValue('IdentString', $string));
    }

    public function findVehicle(FormDataRequest $state): Request
    {
        return $this->createRequest(VehicleList::class, 'POST', 'findVehicle', (array)$state);
    }

    public function findVehicleByPlate(string $countryCode, string $plateNumber, string $catalogToken = null): Request
    {
        return $this->findByPlateNumber(
            (new FormDataRequest($catalogToken))
                ->setFieldValue('CountryCode', $countryCode)
                ->setFieldValue('PlateNumber', $plateNumber)
        );
    }

    public function findByPlateNumber(FormDataRequest $state): Request
    {
        return $this->createRequest(VehicleList::class, 'POST', 'findByPlateNumber', (array)$state);
    }

    public function findVehiclesByOem(string $catalogToken, string $oem, bool $includeReplacements = false): Request
    {
        return $this->findApplicableVehicles(
            (new FormDataRequest($catalogToken))
                ->setFieldValue('PartNumber', $oem)
                ->setFieldValue('IncludeReplacements', $includeReplacements ? 'true' : 'false')
        );
    }

    public function findApplicableVehicles(FormDataRequest $request): Request
    {
        return $this->createRequest(VehicleList::class, 'POST', 'findApplicableVehicles', (array)$request);
    }

    /**
     * Vehicle internals API
     */

    public function getUnits(FilterDataRequest $request): Request
    {
        return $this->createRequest(UnitShortList::class, 'POST', 'getUnits', (array)$request);
    }

    public function getUnitInfo(FilterDataRequest $request): Request
    {
        return $this->createRequest(Unit::class, 'POST', 'getUnitInfo', (array)$request);
    }

    public function getAllPartsByFilter(FilterDataRequest $request, bool $withNames = true): Request
    {
        $filter = new FilterStatefullFormDataRequest($request->token, $request->currentFilterState);
        $filter->filterValues = $request->filterValues;
        $filter->setFieldValue('WithNames', $withNames ? 'true' : 'false');

        return $this->getAllParts($filter);
    }

    public function getPartsByName(FilterDataRequest $request, string $query): Request
    {
        $filter = new FilterStatefullFormDataRequest($request->token, $request->currentFilterState);
        $filter->filterValues = $request->filterValues;
        $filter->setFieldValue('Query', $query);

        return $this->createRequest(PartShortList::class, 'POST', 'detailsFullTextSearch', (array)$filter);
    }

    public function getAllParts(FilterStatefullFormDataRequest $request): Request
    {
        return $this->createRequest(PartShortList::class, 'POST', 'getAllParts', (array)$request);
    }

    public function getAllPartsByVehicle(string $vehicleToken, bool $withNames = true): Request
    {
        $filter = new FilterStatefullFormDataRequest($vehicleToken);
        $filter->setFieldValue('WithNames', $withNames ? 'true' : 'false');

        return $this->getAllParts($filter);
    }

    public function getNavigationTree(FilterDataRequest $request): Request
    {
        return $this->createRequest(CategoryNode::class, 'POST', 'getNavigationTree', (array)$request);
    }

    public function getGroups(FilterDataRequest $request): Request
    {
        return $this->createRequest(GroupNode::class, 'POST', 'getGroups', (array)$request);
    }

    public function getUnitParts(FilterDataRequest $request): Request
    {
        return $this->createRequest(PartSectionsList::class, 'POST', 'getUnitParts', (array)$request);
    }

    public function getGroupParts(FilterDataRequest $request): Request
    {
        return $this->createRequest(PartsListByCategory::class, 'POST', 'getGroupParts', (array)$request);
    }

    public function getGroupPartsAll(FilterDataRequest $request): Request
    {
        return $this->createRequest(PartsListByCategory::class, 'POST', 'getGroupPartsAll', (array)$request);
    }

    public function getPartApplicabilityByFilter(FilterDataRequest $request, string $partNumber, bool $includeReplacements = false): Request
    {
        $filter = new FilterStatefullFormDataRequest($request->token, $request->currentFilterState);
        $filter->filterValues = $request->filterValues;
        $filter->setFieldValue('PartNumber', $partNumber);
        $filter->setFieldValue('IncludeReplacements', $includeReplacements ? 'true' : 'false');

        return $this->getPartApplicability($filter);
    }

    public function getPartApplicability(FilterStatefullFormDataRequest $request): Request
    {
        return $this->createRequest(PartsListByCategory::class, 'POST', 'getPartApplicability', (array)$request);
    }

    public function getPartApplicabilityByVehicle(string $vehicleToken, string $partNumber, bool $includeReplacements = false): Request
    {
        $filter = new FilterStatefullFormDataRequest($vehicleToken);
        $filter->setFieldValue('PartNumber', $partNumber);
        $filter->setFieldValue('IncludeReplacements', $includeReplacements ? 'true' : 'false');

        return $this->getPartApplicability($filter);
    }

    public function getFilter(FilterDataRequest $request): Request
    {
        return $this->createRequest(FilterForm::class, 'POST', 'getFilter', (array)$request);
    }

    /**
     * Customer info API
     */

    public function whoAreMeInfo(): Request
    {
        return $this->createRequest(Customer::class, 'GET', 'whoAreMeInfo');
    }

}