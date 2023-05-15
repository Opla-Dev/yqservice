<?php

namespace tests;

use YQService\oem\Response\PartShort;
use YQService\oem\Response\PartShortList;

class ApplicabilityTest extends Base
{
    public function testApplicabilityGuiFlow()
    {
        $oem = '0415238020'; // TOYOTA-LEXUS part number
        $catalogs = $this->service->findPartReferencesByOem($oem);
        $this->checkPartReferencesList($catalogs);
        $catalog = $catalogs->getCatalog('TOYOTA');
        $this->assertNotEmpty($catalog);

        $vehicles = $catalog->findVehiclesByOem($oem);
        $this->checkVehicleList($vehicles);

        $vehicle = $vehicles->vehicles[0];

        $applicability = $vehicle->getPartApplicability($oem);
        $this->checkApplicability($applicability);

        $vehicles = $this->getCatalogs()->getCatalogByBrand('CITROEN')->findVehiclesByOem($oem);
        $this->assertTrue(count($vehicles->vehicles) == 0);
    }

    public function testAllParts()
    {
        $vehicle = $this->getCitroenVehicle();
        $allParts = $vehicle->getAllParts();
        $this->assertTrue(get_class($allParts) == PartShortList::class);
        $this->assertNotEmpty($allParts->brand);
        $this->assertNotEmpty($allParts->parts);

        $part = $allParts->parts[0];
        $this->assertTrue(get_class($part) == PartShort::class);
        $this->assertNotEmpty($part->partNumber);
        $this->assertNotEmpty($part->partName);
    }
}