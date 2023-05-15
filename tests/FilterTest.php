<?php

namespace tests;

use YQService\oem\Response\UnitShort;
use YQService\oem\Response\UnitShortList;

class FilterTest extends Base
{
    public function testUnitFilters()
    {
        $vehicles = $this->service->findVehicleByVin('VF1FW58H753809237');
        $this->checkVehicleList($vehicles);

        $vehicle = $vehicles->vehicles[0];
        $tree = $vehicle->getNavigationTree();
        $this->checkCategoryNodes($tree->children);

        $node = $this->findCategoryNode($tree, '13 Fuel supply');
        $units = $node->getUnits();
        $this->checkUnitShortList($units);
        $unit = $this->findUnit($units, 'N135330');

        $this->assertTrue($unit->isFilterExists());
        $form = $unit->getFilter();
        $this->checkFilterForm($form);

        $field = $form->fields[0];
        $state = $form->getFilterState();
        $state->setFilterValue($field->name, $field->options[0]->value);

        $units2 = $node->getUnits($state);
        $this->checkUnitShortList($units2);
        $unit2 = $this->findUnit($units2, 'N135330');
        $this->assertFalse($unit2->isFilterExists());

        $unitFull = $unit->getUnitInfo();
        $this->assertTrue($unitFull->isFilterExists());
        $unitFull = $unit->getFilter();
        $this->checkFilterForm($unitFull);
    }

    protected function findUnit(UnitShortList $units, string $code): ?UnitShort
    {
        foreach ($units->units as $unit) {
            if ($unit->code == $code) {
                return $unit;
            }
        }

        return null;
    }

    public function testPartFilters()
    {
        $vehicles = $this->service->findVehicleByVin('VF1FW58H753809237');
        $this->checkVehicleList($vehicles);

        $vehicle = $vehicles->vehicles[0];
        $tree = $vehicle->getNavigationTree();
        $this->checkCategoryNodes($tree->children);

        $node = $this->findCategoryNode($tree, '20 Clutch');
        $units = $node->getUnits();
        $this->checkUnitShortList($units);
        $unit = $this->findUnit($units, 'N200115');

        $this->assertTrue($unit->isFilterExists());
        $parts = $unit->getUnitParts();
        $this->checkPartSectionsList($parts);
        $part = $parts->partSections[0]->parts[0];

        $this->assertTrue($part->isFilterExists());

        $form = $part->getFilter();
        $this->checkFilterForm($form);

        $field = $form->fields[0];
        $state = $form->getFilterState();
        $state->setFilterValue($field->name, $field->options[1]->value);

        $parts2 = $unit->getUnitParts($state);
        $this->checkPartSectionsList($parts2);
        $part2 = $parts2->partSections[0]->parts[0];

        $this->assertFalse($part2->isFilterExists());

    }
}