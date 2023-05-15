<?php

namespace YQService\oem\Response;

use YQService\oem\traits\Forms;

class CatalogList
{
    use Forms;

    /**
     * @var Catalog[]
     */
    public $catalogs = [];

    public function getCatalogByBrand(string $brand): ?Catalog
    {
        foreach ($this->catalogs as $catalog) {
            if ($catalog->brand == $brand) {
                return $catalog;
            }
        }

        return null;
    }

    public function isFindPlateSpported(): bool
    {
        return $this->getFindPlateForm() != null;
    }

    public function getFindPlateForm(): ?Form
    {
        return $this->getForm('FINDVEHICLEBYPLATENUMBER_V2');
    }

    public function isFindOemSupported(): bool
    {
        return $this->getFindOemForm() != null;
    }

    public function getFindOemForm(): ?Form
    {
        return $this->getForm('FINDPARTREFERENCES_V2');
    }

    public function isFindPlateSupported(): bool
    {
        return $this->getFindPlateForm() != null;
    }

    public function getFindVehicleForm(): Form
    {
        return $this->getForm('FINDVEHICLE_V2');
    }
}