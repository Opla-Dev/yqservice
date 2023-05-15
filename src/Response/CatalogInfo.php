<?php

namespace YQService\oem\Response;

use YQService\oem\exceptions\BehaviorException;
use YQService\oem\OEMService;
use YQService\oem\traits\Forms;

class CatalogInfo extends Catalog
{
    use Forms;

    public function getFindVehicleForm(): Form
    {
        return $this->getForm('FINDVEHICLE_V2');
    }

    public function isFindOemSupported(): bool
    {
        return $this->getFindOemForm() != null;
    }

    public function getFindOemForm(): ?Form
    {
        return $this->getForm('FINDAPPLICABLEVEHICLES_V2');
    }

    public function isWizardSupported(): bool
    {
        return $this->getWizardForm() != null;
    }

    public function getWizardForm(): ?Form
    {
        return $this->getForm('WIZARD');
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