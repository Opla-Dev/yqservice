<?php

namespace tests;

use YQService\oem\Response\VehicleList;

class VehicleSearchTest extends Base
{
    public function testPlateSearch()
    {
        $vehicles = $this->service->findVehicleByPlate('SK', 'BA001AB'); // TOYOTA
        $this->checkVehicleList($vehicles);

        $vehicles = $this->service->findVehicleByPlate('SK', 'BA001AB', $this->getCatalogs()->getCatalogByBrand('CITROEN')->token);
        $this->assertTrue(count($vehicles->vehicles) == 0);
    }

    public function testVinSearch()
    {
        $this->getCitroenVehicle();

        $vehicles = $this->service->findVehicleByVin('VF77J5FS0DJ662587', $this->getCatalogs()->getCatalogByBrand('TOYOTA')->token);
        $this->assertTrue(count($vehicles->vehicles) == 0);
    }

    public function testCustomSearch()
    {
        $catalog = $this->getCatalogs()->getCatalogByBrand('DAF');
        $this->checkCatalog($catalog);

        $catalog = $catalog->getCatalogInfo();
        $this->checkCatalogInfo($catalog);

        $form = $catalog->getForm('findByChassisNumber');
        $state = $form->getFormState();
        $state->setFieldValue('chassis', 'EB100567');

        $vehicles = $this->service->findVehicleOperation($state);
        $this->checkVehicleList($vehicles);
    }

    public function testWizard()
    {
        $catalog = $this->getCatalogs()->getCatalogByBrand('TOYOTA')->getCatalogInfo();

        $wizard = $catalog->getWizardForm();
        $this->checkForm($wizard);

        $field = $wizard->fields[0];
        $this->assertTrue(count($field->options) > 0);

        $state = $wizard->getFormState();
        $state->setFieldValue($field->name, $field->options[0]->value);

        $wizard2 = $this->service->getOperationForm($state);
        $this->checkForm($wizard2);
        $this->assertTrue(count($wizard2->fields) > 0);
        $this->assertTrue(count($wizard2->fields[0]->options) == 1);

        $field2 = $wizard2->fields[2];
        $this->assertTrue(count($field2->options) > 0);

        $state2 = $wizard2->getFormState();
        $state2->setFieldValue($field2->name, $field2->options[0]->value);
        $state2->cancelFieldValue();

        $wizard3 = $this->service->getOperationForm($state2);
        $this->checkForm($wizard3);
        $this->assertTrue(count($wizard3->fields) > 0);
        $this->assertTrue(count($wizard3->fields[2]->options) == 1);

        $vehicles = $this->service->findVehicleOperation($wizard3->getFormState());
        $this->assertTrue(get_class($vehicles) == VehicleList::class);
        $this->assertTrue(count($vehicles->vehicles) > 0);
    }
}