<?php

namespace tests;

class CatalogTest extends Base
{
    public function testCatalogs()
    {
        $catalogs = $this->service->catalogs();
        $this->checkCatalogList($catalogs);

        $catalog = $catalogs->getCatalogByBrand('TOYOTA');
        $this->checkCatalog($catalog);

        $catalogInfo = $catalog->getCatalogInfo();
        $this->checkCatalogInfo($catalogInfo);

        $catalogShort = $this->service->getCatalogShort($catalogInfo->token);
        $this->checkCatalogShort($catalogShort);
    }
}