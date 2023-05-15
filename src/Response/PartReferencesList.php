<?php

namespace YQService\oem\Response;

class PartReferencesList
{
    /**
     * @var PartReferences[]
     */
    public $partReferences = [];

    public function getCatalog(string $brand): ?Catalog
    {
        foreach ($this->partReferences as $reference) {
            foreach ($reference->catalogs as $catalog) {
                if ($catalog->brand == $brand) {
                    return $catalog;
                }
            }
        }
        return null;
    }
}