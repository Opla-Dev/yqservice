<?php

namespace tests;

use YQService\oem\Response\CategoryNode;
use YQService\oem\Response\Part;
use YQService\oem\Response\PartSection;
use YQService\oem\Response\PartSectionsList;

class NavigationTreeTest extends Base
{
    public function testNavigationTree()
    {
        $vehicle = $this->getCitroenVehicle();

        $tree = $vehicle->getDefaultNavigationTree();
        $this->checkCategoryNodes($tree->children);

        $node = $this->findCategoryNode($tree, 'gearbox');
        $this->assertTrue(get_class($node) == CategoryNode::class);
        $this->assertTrue($node->isGetUnitsAvailable());

        $units = $node->getUnits();
        $this->checkUnitShortList($units);

        $unitShort = $units->units[0];
        $unitShortParts = $unitShort->getUnitParts();

        $this->assertTrue(get_class($unitShortParts) == PartSectionsList::class);
        $this->checkPartSectionList($unitShortParts);

        $unit = $unitShort->getUnitInfo();
        $this->checkUnit($unit);

        $unitShortParts = $unit->getUnitParts();
        $this->checkPartSectionList($unitShortParts);
    }

    protected function checkPartSectionList(PartSectionsList $sections)
    {
        $this->assertNotEmpty($sections->partSections);

        $section = $sections->partSections[0];
        $this->assertTrue(get_class($section) == PartSection::class);
        $this->assertNotEmpty($section->parts);

        $part = $section->parts[0];
        $this->assertTrue(get_class($part) == Part::class);
        $this->assertNotEmpty($part->qty);
        $this->assertNotEmpty($part->partNumber);
        $this->assertNotEmpty($part->partNumberFormatted);
        $this->assertNotEmpty($part->partName);
        $this->assertNotEmpty($part->displayName);
        $this->assertNotEmpty($part->attributes);
        $this->assertNotEmpty($part->areaCode);
    }

}