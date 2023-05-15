<?php

namespace tests;

use YQService\oem\Response\GroupNode;

class QuickGroupTest extends Base
{
    public function testQuickGroup()
    {
        $vehicle = $this->getCitroenVehicle();
        $this->assertTrue($vehicle->isGroupsSupported());

        $groups = $vehicle->getGroups();
        $this->checkGroupNodes($groups->children);

        $group = $this->searchNode($groups, 'Air Filter');
        $this->assertTrue(get_class($group) == GroupNode::class);

        $partsListByCategory = $group->getGroupParts();
        $this->checkApplicability($partsListByCategory);

        $partsListByCategoryAll = $group->getGroupPartsAll();
        $this->checkApplicability($partsListByCategoryAll);

        $partsListByCategoryCount = $this->countNodes($partsListByCategory);
        $partsListByCategoryAllCount = $this->countNodes($partsListByCategoryAll);

        $this->assertTrue($partsListByCategoryCount < $partsListByCategoryAllCount);
    }

    protected function searchNode(GroupNode $node, string $name): ?GroupNode
    {
        if ($node->name == $name) {
            return $node;
        }

        foreach ($node->children as $childNode) {
            $result = $this->searchNode($childNode, $name);
            if ($result) {
                return $result;
            }
        }

        return null;
    }
}