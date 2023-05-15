<?php

namespace tests;

use Exception;
use PHPUnit\Framework\TestCase;
use YQService\oem\Config;
use YQService\oem\OEMService;
use YQService\oem\Response\AttrNode;
use YQService\oem\Response\Catalog;
use YQService\oem\Response\CatalogInfo;
use YQService\oem\Response\CatalogList;
use YQService\oem\Response\CatalogShort;
use YQService\oem\Response\CategoryNode;
use YQService\oem\Response\CategoryShort;
use YQService\oem\Response\Field;
use YQService\oem\Response\FilterForm;
use YQService\oem\Response\Form;
use YQService\oem\Response\GroupNode;
use YQService\oem\Response\ImageMap;
use YQService\oem\Response\ImageMapArea;
use YQService\oem\Response\Link;
use YQService\oem\Response\MeasuredValue;
use YQService\oem\Response\NavigationLink;
use YQService\oem\Response\Option;
use YQService\oem\Response\Part;
use YQService\oem\Response\PartReferences;
use YQService\oem\Response\PartReferencesList;
use YQService\oem\Response\PartsByCategory;
use YQService\oem\Response\PartsByUnit;
use YQService\oem\Response\PartSection;
use YQService\oem\Response\PartSectionsList;
use YQService\oem\Response\PartShort;
use YQService\oem\Response\PartShortList;
use YQService\oem\Response\PartsListByCategory;
use YQService\oem\Response\Property;
use YQService\oem\Response\Unit;
use YQService\oem\Response\UnitShort;
use YQService\oem\Response\UnitShortList;
use YQService\oem\Response\Vehicle;
use YQService\oem\Response\VehicleList;
use YQService\oem\Response\VehicleShort;

class Base extends TestCase
{
    /**
     * @var OEMService
     */
    protected $service = null;

    public function setUp(): void
    {
        $configData = file_get_contents(__DIR__ . '/config.json');
        if (!$configData) {
            throw new Exception('Prepare config.json file with your login and password');
        }
        $config = json_decode($configData, true);
        $this->service = new OEMService(new Config($config['oem']));
    }

    protected function getCatalogs(): CatalogList
    {
        static $catalogs = null;
        if (!$catalogs) {
            $catalogs = $this->service->catalogs();
        }
        return $catalogs;
    }

    /**********************************************************
     *                  CATALOGS
     *********************************************************/

    /**
     * @param CatalogInfo $catalogInfo
     */
    protected function checkCatalogInfo($catalogInfo)
    {
        $this->assertNotNull($catalogInfo);
        $this->assertTrue(get_class($catalogInfo) == CatalogInfo::class);
        if ($catalogInfo->isFindOemSupported()) {
            $this->assertNotEmpty($catalogInfo->getFindOemForm());
        }
        $this->assertNotEmpty($catalogInfo->getFindVehicleForm());
        if ($catalogInfo->isWizardSupported()) {
            $this->assertNotEmpty($catalogInfo->getWizardForm());
        }
        $this->assertNotEmpty($catalogInfo->getFindVehicleForm());
        $this->checkCatalog($catalogInfo);
    }

    /**
     * @param Catalog|CatalogInfo $catalog
     */
    protected function checkCatalog($catalog)
    {
        $this->assertNotNull($catalog);
        $this->assertTrue(get_class($catalog) == Catalog::class || is_subclass_of($catalog, Catalog::class));
        $this->checkCatalogShort($catalog);
        $this->checkLinks($catalog->links);
    }

    /**
     * @param CatalogShort|Catalog|CatalogInfo $catalogShort
     */
    protected function checkCatalogShort($catalogShort)
    {
        $this->assertTrue(get_class($catalogShort) == CatalogShort::class || is_subclass_of($catalogShort, CatalogShort::class));
        $this->assertNotNull($catalogShort);
        $this->assertNotEmpty($catalogShort->token);
        $this->assertNotEmpty($catalogShort->brand);
        $this->assertNotEmpty($catalogShort->name);
    }

    /**
     * @param Link[] $links
     */
    protected function checkLinks($links)
    {
        $this->assertTrue(is_array($links));
        foreach ($links as $link) {
            $this->checkLink($link);
        }
    }

    /**
     * @param Link $link
     */
    protected function checkLink($link)
    {
        $this->assertTrue(get_class($link) == Link::class);
        $this->assertNotEmpty($link->token);
        $this->assertNotEmpty($link->action);
        $this->assertNotEmpty($link->label);
    }

    /**
     * @param CatalogList $catalogs
     */
    protected function checkCatalogList($catalogs)
    {
        $this->assertTrue(get_class($catalogs) == CatalogList::class);
        $this->assertNotEmpty($catalogs->catalogs);
    }

    /**********************************************************
     *                  VEHICLES
     *********************************************************/

    protected function getCitroenVehicle()
    {
        $vehicles = $this->service->findVehicleByVin('VF77J5FS0DJ662587');   // CITROEN
        $this->checkVehicleList($vehicles);

        return $vehicles->vehicles[0];
    }

    /**
     * @param VehicleList $list
     */
    protected function checkVehicleList($list)
    {
        $this->assertTrue(get_class($list) == VehicleList::class);
        $this->assertTrue(count($list->vehicles) > 0);

        $vehicle = $list->vehicles[0];
        $this->checkVehicleShort($vehicle);

        $vehicleInfo = $vehicle->getVehicleInfo();
        $this->checkVehicle($vehicleInfo);
    }

    /**********************************************************
     *                  LINKS
     *********************************************************/

    /**
     * @param VehicleShort $vehicle
     */
    protected function checkVehicleShort($vehicle)
    {
        $this->assertTrue(get_class($vehicle) == VehicleShort::class || is_subclass_of($vehicle, VehicleShort::class));
        $this->assertNotEmpty($vehicle->token);
        $this->assertNotEmpty($vehicle->type);
        $this->assertNotEmpty($vehicle->brand);
        $this->assertNotEmpty($vehicle->model);
        $this->assertNotEmpty($vehicle->attributes);
        $this->assertNotEmpty($vehicle->sysProperties);
        $this->assertEmpty($vehicle->currentFilterState);
        $this->checkLinks($vehicle->links);
        $this->checkProperties($vehicle->sysProperties);
        $this->checkNavigationLinks($vehicle->navigationLinks);
        $this->checkAttrNodes($vehicle->attributes);

        $catalog = $vehicle->getCatalogShort();
        $this->checkCatalogShort($catalog);
    }

    /**
     * @param Property[] $properties
     */
    protected function checkProperties($properties)
    {
        $this->assertTrue(is_array($properties));
        foreach ($properties as $property) {
            $this->checkProperty($property);
        }
    }

    /**********************************************************
     *                  NAVIGATIONLINKS
     *********************************************************/

    /**
     * @param Property $property
     */
    protected function checkProperty($property)
    {
        $this->assertTrue(get_class($property) == Property::class);
        $this->assertNotEmpty($property->code);
        $this->assertNotEmpty($property->value);
    }

    /**
     * @param NavigationLink[] $links
     */
    protected function checkNavigationLinks($links)
    {
        $this->assertTrue(is_array($links));
        foreach ($links as $link) {
            $this->checkNavigationLink($link);
        }
    }

    /**********************************************************
     *                  AttrNode
     *********************************************************/

    /**
     * @param NavigationLink $link
     */
    protected function checkNavigationLink($link)
    {
        $this->assertTrue(get_class($link) == NavigationLink::class);
        $this->assertNotEmpty($link->token);
        $this->assertNotEmpty($link->action);
        $this->assertNotEmpty($link->label);
        $this->assertNotEmpty($link->code);
    }

    /**
     * @param AttrNode[] $nodes
     */
    protected function checkAttrNodes($nodes)
    {
        $this->assertTrue(is_array($nodes));
        foreach ($nodes as $node) {
            $this->checkAttrNode($node);
        }
    }

    /**********************************************************
     *                  Forms
     *********************************************************/

    /**
     * @param AttrNode $node
     */
    protected function checkAttrNode($node)
    {
        $this->assertTrue(get_class($node) == AttrNode::class);
        if (!$node->children) {
            $this->assertNotEmpty($node->values);
//            $this->assertNotEmpty($node->label);
            $this->assertNotEmpty($node->code);
        }
        $this->assertNotEmpty($node->type);
        $this->checkAttrNodes($node->children);
    }

    /**
     * @param Vehicle $vehicle
     */
    protected function checkVehicle($vehicle)
    {
        $this->assertTrue(get_class($vehicle) == Vehicle::class);
        $this->checkVehicleShort($vehicle);
        $this->checkForms($vehicle->forms);

        $catalog = $vehicle->getCatalogShort();
        $this->checkCatalogShort($catalog);
    }

    /**
     * @param Form[] $forms
     */
    protected function checkForms($forms)
    {
        $this->assertTrue(is_array($forms));
        foreach ($forms as $form) {
            $this->checkForm($form);
        }
    }

    /**
     * @param Form $form
     */
    protected function checkForm($form)
    {
        $this->assertTrue(get_class($form) == Form::class);
        $this->assertNotEmpty($form->action || $form->operationName);
        $this->assertNotEmpty($form->label);
        $this->assertNotEmpty($form->token);
        $this->assertNotEmpty($form->fields);
        $this->checkFields($form->fields);
    }

    /**
     * @param Field[] $fields
     */
    protected function checkFields($fields)
    {
        $this->assertTrue(is_array($fields));
        foreach ($fields as $form) {
            $this->checkField($form);
        }
    }

    /**
     * @param Field $field
     */
    protected function checkField($field)
    {
        $this->assertTrue(is_subclass_of($field, Field::class));
        $this->assertNotEmpty($field->type);
        $this->assertNotEmpty($field->name);
        $this->assertNotEmpty($field->label);
        if ($field->type == 'select') {
            $this->checkOptions($field->options);
        }
    }

    /**
     * @param Option[] $options
     */
    protected function checkOptions($options)
    {
        $this->assertTrue(is_array($options));
        foreach ($options as $form) {
            $this->checkOption($form);
        }
    }

    /**********************************************************
     *                  SYSPROPERTIES
     *********************************************************/

    /**
     * @param Option $object
     */
    protected function checkOption($object)
    {
        $this->assertTrue(get_class($object) == Option::class);
        $this->assertNotEmpty($object->value);
        $this->assertNotEmpty($object->label);
    }

    /**
     * @param FilterForm $form
     */
    protected function checkFilterForm($form)
    {
        $this->assertTrue(get_class($form) == FilterForm::class);
        $this->assertNotEmpty($form->action || $form->operationName);
        $this->assertNotEmpty($form->label);
        $this->assertNotEmpty($form->token);
        $this->assertNotEmpty($form->fields);
        $this->checkFields($form->fields);
    }

    /**********************************************************
     *                  PartReferencesList
     *********************************************************/

    /**
     * @param PartReferencesList $list
     */
    protected function checkPartReferencesList($list)
    {
        $this->assertTrue(get_class($list) == PartReferencesList::class);
        $this->assertNotEmpty($list->partReferences);
        $this->assertTrue(is_array($list->partReferences));
        foreach ($list->partReferences as $reference) {
            $this->checkPartReferences($reference);
        }
    }

    /**
     * @param PartReferences $partReferences
     */
    protected function checkPartReferences($partReferences)
    {
        $this->assertTrue(get_class($partReferences) == PartReferences::class);
        $this->assertNotEmpty($partReferences->partNumber);
        $this->assertNotEmpty($partReferences->partName);
        $this->assertTrue(is_array($partReferences->catalogs));
        foreach ($partReferences->catalogs as $catalog) {
            $this->checkCatalog($catalog);
        }
    }

    /**********************************************************
     *                  PartsListByCategory
     *********************************************************/

    /**
     * @param PartsListByCategory $list
     */
    protected function checkPartsListByCategory($list)
    {
        $this->assertTrue(get_class($list) == PartsListByCategory::class);
        $this->assertNotEmpty($list->categories);
        $this->assertNotEmpty($list->filter);
        foreach ($list->categories as $category) {
            $this->checkPartsByCategory($category);
        }
    }

    /**
     * @param PartsByCategory $category
     */
    protected function checkPartsByCategory($category)
    {
        $this->assertTrue(get_class($category) == PartsByCategory::class);
        $this->assertNotEmpty($category->category);
        $this->checkCategoryShort($category->category);
        $this->assertNotEmpty($category->units);
        $this->assertTrue(is_array($category->units));
        foreach ($category->units as $unit) {
            $this->checkPartsByUnit($unit);
        }
    }

    /**
     * @param CategoryShort $category
     */
    protected function checkCategoryShort($category)
    {
        $this->assertTrue(get_class($category) == CategoryShort::class);
        $this->assertNotEmpty($category->name);
        $this->assertNotEmpty($category->code);
    }

    /**
     * @param PartsByUnit $unit
     */
    protected function checkPartsByUnit($unit)
    {
        $this->assertTrue(get_class($unit) == PartsByUnit::class);
        $this->assertNotEmpty($unit->unit);
        $this->checkUnitShort($unit->unit);
        $this->assertNotEmpty($unit->partSections);
        $this->checkPartSections($unit->partSections);
    }

    /**
     * @param UnitShort $unitShort
     */
    protected function checkUnitShort($unitShort)
    {
        $this->assertTrue(get_class($unitShort) == UnitShort::class);
        $this->assertNotEmpty($unitShort->name);
        $this->assertNotEmpty($unitShort->token);
        $this->assertNotEmpty($unitShort->code);
//        $this->assertNotEmpty($unitShort->description);
        $this->assertNotEmpty($unitShort->imageNames);
        $this->assertTrue(is_array($unitShort->imageNames));
        $this->assertTrue(count($unitShort->imageNames) > 0);
        $this->assertNotEmpty($unitShort->getImageNames(UnitShort::IMAGE_SIZE_150));
        $this->checkAttrNodes($unitShort->attributes);
        $this->checkLinks($unitShort->links);
    }

    /**
     * @param PartSection[] $partSections
     */
    protected function checkPartSections($partSections)
    {
        $this->assertTrue(is_array($partSections));
        foreach ($partSections as $partSection) {
            $this->checkPartSection($partSection);
        }
    }

    /**
     * @param PartSection $partSection
     */
    protected function checkPartSection($partSection)
    {
        $this->assertTrue(get_class($partSection) == PartSection::class);
//        $this->assertNotEmpty($partSection->title);
        $this->assertNotEmpty($partSection->parts);
        $this->assertTrue(is_array($partSection->parts));
        $this->assertTrue(count($partSection->parts) > 0);
        $this->checkParts($partSection->parts);
    }

    /**
     * @param Part[] $parts
     */
    protected function checkParts($parts)
    {
        $this->assertTrue(is_array($parts));
        foreach ($parts as $part) {
            $this->checkPart($part);
        }
    }

    /**********************************************************
     *                  PartShortList
     *********************************************************/

    /**
     * @param Part $part
     */
    protected function checkPart($part)
    {
        $this->assertTrue(get_class($part) == Part::class);
//        $this->assertNotEmpty($part->partName);
        $this->assertNotEmpty($part->partNumber);
//        $this->assertNotEmpty($part->partNumberFormatted); //TODO: uncomment
//        $this->assertNotEmpty($part->displayName);
        $this->assertNotEmpty($part->areaCode);
        $this->checkMeasuredValue($part->qty);
        $this->checkAttrNodes($part->attributes);
        $this->checkLinks($part->refs);
        $this->checkPartSections($part->related);
    }

    /**
     * @param MeasuredValue $quantity
     */
    protected function checkMeasuredValue($quantity)
    {
        if ($quantity == null) {
            return;
        }

        $this->assertTrue(get_class($quantity) == MeasuredValue::class);
        $this->assertNotEmpty($quantity->value);
        $this->assertNotEmpty($quantity->units);
        $this->assertNotEmpty($quantity->note);
    }

    /**********************************************************
     *                  GroupNode
     *********************************************************/

    /**
     * @param PartShortList $list
     */
    protected function checkPartShortList($list)
    {
        $this->assertTrue(get_class($list) == PartShortList::class);
        $this->assertNotEmpty($list->brand);
        $this->assertTrue(is_array($list->parts));
        foreach ($list->parts as $part) {
            $this->checkPartShort($part);
        }
    }

    /**
     * @param PartShort $partShort
     */
    protected function checkPartShort($partShort)
    {
        $this->assertTrue(get_class($partShort) == PartShort::class);
        $this->assertNotEmpty($partShort->partNumber);
        $this->assertNotEmpty($partShort->partName);
    }

    /**********************************************************
     *                  CategoryNode
     *********************************************************/

    /**
     * @param GroupNode[] $list
     */
    protected function checkGroupNodes($list)
    {
        $this->assertTrue(is_array($list));
        foreach ($list as $node) {
            $this->checkGroupNode($node);
        }
    }

    /**
     * @param GroupNode $node
     */
    protected function checkGroupNode($node)
    {
        $this->assertTrue(get_class($node) == GroupNode::class);
//        $this->assertNotEmpty($node->token);
        $this->assertNotEmpty($node->name);
//        $this->assertNotEmpty($node->code);
        $this->checkGroupNodes($node->children);
        $this->checkLinks($node->links);
    }

    /**********************************************************
     *                  UnitShortList
     *********************************************************/

    /**
     * @param CategoryNode[] $list
     */
    protected function checkCategoryNodes($list)
    {
        $this->assertTrue(is_array($list));
        foreach ($list as $node) {
            $this->checkCategoryNode($node);
        }
    }

    /**
     * @param CategoryNode $node
     */
    protected function checkCategoryNode($node)
    {
        $this->assertTrue(get_class($node) == CategoryNode::class);
        $this->assertNotEmpty($node->name);
//        $this->assertNotEmpty($node->code);
        $this->checkCategoryNodes($node->children);
        $this->checkLinks($node->links);
    }

    /**
     * @param UnitShortList $list
     */
    protected function checkUnitShortList($list)
    {
        $this->assertTrue(get_class($list) == UnitShortList::class);
        $this->assertTrue(is_array($list->units));
        foreach ($list->units as $unit) {
            $this->checkUnitShort($unit);
        }
    }

    /**********************************************************
     *                  UnitShortList
     *********************************************************/

    /**
     * @param Unit $unitShort
     */
    protected function checkUnit($unitShort)
    {
        $this->assertTrue(get_class($unitShort) == Unit::class);
        $this->assertNotEmpty($unitShort->name);
        $this->assertNotEmpty($unitShort->token);
        $this->assertNotEmpty($unitShort->code);
//        $this->assertNotEmpty($unitShort->description);
        $this->checkImageMaps($unitShort->imageMaps);
        $this->checkAttrNodes($unitShort->attributes);
        $this->checkLinks($unitShort->links);
    }

    /**
     * @param ImageMap[] $list
     */
    protected function checkImageMaps($list)
    {
        $this->assertTrue(is_array($list));
        foreach ($list as $node) {
            $this->checkImageMap($node);
        }
    }

    /**********************************************************
     *                  ImageMapArea
     *********************************************************/

    /**
     * @param ImageMap $imageMap
     */
    protected function checkImageMap($imageMap)
    {
        $this->assertTrue(get_class($imageMap) == ImageMap::class);
        $this->assertNotEmpty($imageMap->imageName);
        $this->checkImageMapAreas($imageMap->areas);
        $this->assertNotEmpty($imageMap->getImageNames(ImageMap::IMAGE_SIZE_150));
    }

    /**
     * @param ImageMapArea[] $list
     */
    protected function checkImageMapAreas($list)
    {
        $this->assertTrue(is_array($list));
        foreach ($list as $area) {
            $this->checkImageMapArea($area);
        }
    }

    /**********************************************************
     *                  PartSectionsList
     *********************************************************/

    /**
     * @param ImageMapArea $area
     */
    protected function checkImageMapArea($area)
    {
        $this->assertTrue(get_class($area) == ImageMapArea::class);
        $this->assertNotEmpty($area->x1);
        $this->assertNotEmpty($area->y1);
        $this->assertNotEmpty($area->x2);
        $this->assertNotEmpty($area->y2);
        $this->assertNotEmpty($area->areaCode);
    }

    /**
     * @param PartSectionsList $list
     */
    protected function checkPartSectionsList($list)
    {
        $this->assertTrue(get_class($list) == PartSectionsList::class);
        $this->assertTrue(is_array($list->partSections));
        foreach ($list->partSections as $section) {
            $this->checkPartSection($section);
        }
    }

    protected function findCategoryNode(CategoryNode $node, string $name): ?CategoryNode
    {
        if ($node->name == $name) {
            return $node;
        }

        foreach ($node->children as $childNode) {
            $result = $this->findCategoryNode($childNode, $name);
            if ($result) {
                return $result;
            }
        }

        return null;
    }


    /**
     * @param PartsListByCategory $applicability
     */
    protected function checkApplicability(PartsListByCategory $applicability): void
    {
        $this->assertTrue(get_class($applicability) == PartsListByCategory::class);
        $this->assertTrue(count($applicability->categories) > 0);

        $category = $applicability->categories[0];
        $this->assertTrue(get_class($category) == PartsByCategory::class);
        $this->assertTrue(count($category->units) > 0);

        $unit = $category->units[0];
        $this->assertTrue(get_class($unit) == PartsByUnit::class);
        $this->assertTrue(count($unit->partSections) > 0);

        $partSection = $unit->partSections[0];
        $this->assertTrue(get_class($partSection) == PartSection::class);
        $this->assertTrue(count($partSection->parts) > 0);

        $part = $partSection->parts[0];
        $this->assertTrue(get_class($part) == Part::class);
    }

    protected function countNodes(PartsListByCategory $applicability): int
    {
        $count = 0;
        foreach ($applicability->categories as $category) {
            foreach ($category->units as $unit) {
                foreach ($unit->partSections as $section) {
                    foreach ($section->parts as $part) {
                        $count++;
                    }
                    $count++;
                }
                $count++;
            }
            $count++;
        }

        return $count;
    }
}