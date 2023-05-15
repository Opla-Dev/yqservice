<?php

namespace YQService\oem;

use YQService\oem\exceptions\YQException;
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

class OEMService extends ServiceWrapper
{
    protected static $service = null;

    public function __construct(Config $config)
    {
        parent::__construct($config);
        self::$service = $this;
    }

    public static function getService(): ?OEMService
    {
        return self::$service;
    }

    /**
     * Catalogs API
     */

    /**
     * @return CatalogList
     * @throws YQException
     */
    public function catalogs(): CatalogList
    {
        return $this->query(RequestFactory::getFactory()->catalogs());
    }

    /**
     * @param string $catalogToken
     * @return CatalogInfo
     * @throws YQException
     */
    public function getCatalogInfo(string $catalogToken): CatalogInfo
    {
        return $this->query(RequestFactory::getFactory()->getCatalogInfo($catalogToken));
    }

    /**
     * @param string $catalogToken
     * @return CatalogShort
     * @throws YQException
     */
    public function getCatalogShort(string $catalogToken): CatalogShort
    {
        return $this->query(RequestFactory::getFactory()->getCatalogShort($catalogToken));
    }

    /**
     * @param FormDataRequest $state
     * @return PartReferencesList
     * @throws YQException
     */
    public function findPartReferences(FormDataRequest $state): PartReferencesList
    {
        return $this->query(RequestFactory::getFactory()->findPartReferences($state));
    }

    /**
     * @param string $oem
     * @return PartReferencesList
     * @throws YQException
     */
    public function findPartReferencesByOem(string $oem): PartReferencesList
    {
        return $this->query(RequestFactory::getFactory()->findPartReferencesByOem($oem));
    }

    /**
     * Vehicles API
     */

    /**
     * @param FormDataRequest $state
     * @return VehicleList
     * @throws YQException
     */
    public function findVehicle(FormDataRequest $state): VehicleList
    {
        return $this->query(RequestFactory::getFactory()->findVehicle($state));
    }

    /**
     * @param FormDataRequest $state
     * @return VehicleList
     * @throws YQException
     */
    public function findByPlateNumber(FormDataRequest $state): VehicleList
    {
        return $this->query(RequestFactory::getFactory()->findByPlateNumber($state));
    }

    /**
     * @param FilterDataRequest $request
     * @return Vehicle
     * @throws YQException
     */
    public function getVehicleInfo(FilterDataRequest $request): Vehicle
    {
        return $this->query(RequestFactory::getFactory()->getVehicleInfo($request));
    }

    /**
     * @param string $string
     * @param string|null $catalogToken
     * @return VehicleList
     * @throws YQException
     */
    public function findVehicleByVin(string $string, ?string $catalogToken = null): VehicleList
    {
        return $this->query(RequestFactory::getFactory()->findVehicleByVin($string, $catalogToken));
    }

    /**
     * @param FormDataRequest $state
     * @return Form
     * @throws YQException
     */
    public function getOperationForm(FormDataRequest $state): Form
    {
        return $this->query(RequestFactory::getFactory()->getOperationForm($state));
    }

    /**
     * @param FormDataRequest $request
     * @return VehicleList
     * @throws YQException
     */
    public function findApplicableVehicles(FormDataRequest $request): VehicleList
    {
        return $this->query(RequestFactory::getFactory()->findApplicableVehicles($request));
    }

    /**
     * @param FormDataRequest $state
     * @return VehicleList
     * @throws YQException
     */
    public function findVehicleOperation(FormDataRequest $state): VehicleList
    {
        return $this->query(RequestFactory::getFactory()->findVehicleOperation($state));
    }

    /**
     * @param string $countryCode
     * @param string $plateNumber
     * @param string|null $catalogToken
     * @return VehicleList
     * @throws YQException
     */
    public function findVehicleByPlate(string $countryCode, string $plateNumber, string $catalogToken = null): VehicleList
    {
        return $this->query(RequestFactory::getFactory()->findVehicleByPlate($countryCode, $plateNumber, $catalogToken));
    }

    /**
     * @param string $catalogToken
     * @param string $oem
     * @param bool $includeReplacements
     * @return VehicleList
     * @throws YQException
     */
    public function findVehiclesByOem(string $catalogToken, string $oem, bool $includeReplacements = false): VehicleList
    {
        return $this->query(RequestFactory::getFactory()->findVehiclesByOem($catalogToken, $oem, $includeReplacements));
    }

    /**
     * Vehicle internals API
     */

    /**
     * @param FilterDataRequest $request
     * @return CategoryNode
     * @throws YQException
     */
    public function getNavigationTree(FilterDataRequest $request): CategoryNode
    {
        return $this->query(RequestFactory::getFactory()->getNavigationTree($request));
    }

    /**
     * @param FilterDataRequest $request
     * @return GroupNode
     * @throws YQException
     */
    public function getGroups(FilterDataRequest $request): GroupNode
    {
        return $this->query(RequestFactory::getFactory()->getGroups($request));
    }

    /**
     * @param FilterDataRequest $request
     * @return UnitShortList
     * @throws YQException
     */
    public function getUnits(FilterDataRequest $request): UnitShortList
    {
        return $this->query(RequestFactory::getFactory()->getUnits($request));
    }

    /**
     * @param FilterDataRequest $request
     * @return Unit
     * @throws YQException
     */
    public function getUnitInfo(FilterDataRequest $request): Unit
    {
        return $this->query(RequestFactory::getFactory()->getUnitInfo($request));
    }

    /**
     * @param FilterStatefullFormDataRequest $request
     * @return PartShortList
     * @throws YQException
     */
    public function getAllParts(FilterStatefullFormDataRequest $request): PartShortList
    {
        return $this->query(RequestFactory::getFactory()->getAllParts($request));
    }

    /**
     * @param FilterDataRequest $request
     * @param bool $withNames
     * @return PartShortList
     * @throws YQException
     */
    public function getAllPartsByFilter(FilterDataRequest $request, bool $withNames = true): PartShortList
    {
        return $this->query(RequestFactory::getFactory()->getAllPartsByFilter($request, $withNames));
    }

    /**
     * @param string $token
     * @param bool $withNames
     * @return PartShortList
     * @throws YQException
     */
    public function getPartsByName(FilterDataRequest $request, string $query): PartShortList
    {
        return $this->query(RequestFactory::getFactory()->getPartsByName($request, $query));
    }

    /**
     * @param string $token
     * @param bool $withNames
     * @return PartShortList
     * @throws YQException
     */
    public function getAllPartsByVehicle(string $token, bool $withNames = true): PartShortList
    {
        return $this->query(RequestFactory::getFactory()->getAllPartsByVehicle($token, $withNames));
    }

    /**
     * @param FilterDataRequest $request
     * @return PartSectionsList
     * @throws YQException
     */
    public function getUnitParts(FilterDataRequest $request): PartSectionsList
    {
        return $this->query(RequestFactory::getFactory()->getUnitParts($request));
    }

    /**
     * @param FilterDataRequest $request
     * @return PartsListByCategory
     * @throws YQException
     */
    public function getGroupParts(FilterDataRequest $request): PartsListByCategory
    {
        return $this->query(RequestFactory::getFactory()->getGroupParts($request));
    }

    /**
     * @param FilterDataRequest $request
     * @return PartsListByCategory
     * @throws YQException
     */
    public function getGroupPartsAll(FilterDataRequest $request): PartsListByCategory
    {
        return $this->query(RequestFactory::getFactory()->getGroupPartsAll($request));
    }

    /**
     * @param FilterStatefullFormDataRequest $request
     * @return PartsListByCategory
     * @throws YQException
     */
    public function getPartApplicability(FilterStatefullFormDataRequest $request): PartsListByCategory
    {
        return $this->query(RequestFactory::getFactory()->getPartApplicability($request));
    }

    /**
     * @param FilterDataRequest $request
     * @param string $partNumber
     * @param bool $includeReplacements
     * @return PartsListByCategory
     * @throws YQException
     */
    public function getPartApplicabilityByFilter(FilterDataRequest $request, string $partNumber, bool $includeReplacements = false): PartsListByCategory
    {
        return $this->query(RequestFactory::getFactory()->getPartApplicabilityByFilter($request, $partNumber, $includeReplacements));
    }

    /**
     * @param string $vehicleToken
     * @param string $partNumber
     * @param bool $includeReplacements
     * @return PartsListByCategory
     * @throws YQException
     */
    public function getPartApplicabilityByVehicle(string $vehicleToken, string $partNumber, bool $includeReplacements = false): PartsListByCategory
    {
        return $this->query(RequestFactory::getFactory()->getPartApplicabilityByVehicle($vehicleToken, $partNumber, $includeReplacements));
    }

    /**
     * @param FilterDataRequest $request
     * @return FilterForm
     * @throws YQException
     */
    public function getFilter(FilterDataRequest $request): FilterForm
    {
        return $this->query(RequestFactory::getFactory()->getFilter($request));
    }

    /**
     * Customer info API
     */

    /**
     * @return Customer
     * @throws YQException
     */
    public function whoAreMeInfo(): Customer
    {
        return $this->query(RequestFactory::getFactory()->whoAreMeInfo());
    }

}