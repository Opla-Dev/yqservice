<?php

namespace YQService\oem\Response;

use YQService\oem\exceptions\BehaviorException;
use YQService\oem\OEMService;
use YQService\oem\traits\Token;

class CatalogShort
{
    use Token;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $brand;

    /**
     * @var bool
     */
    public $archived;


    /**
     * @throws BehaviorException
     */
    public function getCatalogInfo(): CatalogInfo
    {
        return OEMService::getService()->getCatalogInfo($this->token);
    }

    /**
     * VIN/Frame search
     * @throws BehaviorException
     */
    public function findVehicleByVin(string $string)
    {
        OEMService::getService()->findVehicleByVin($string, $this->token);
    }

    /**
     * VIN/Frame search
     * @throws BehaviorException
     */
    public function findVehicleByPlate(string $countryCode, string $plateNumber)
    {
        OEMService::getService()->findVehicleByPlate($countryCode, $plateNumber, $this->token);
    }

    /**
     * @throws BehaviorException
     */
    public function findVehiclesByOem(string $oem, bool $includeReplacements = false): VehicleList
    {
        return OEMService::getService()->findVehiclesByOem($this->token, $oem, $includeReplacements);
    }
}