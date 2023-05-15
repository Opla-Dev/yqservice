<?php

namespace YQService\oem\Response;

use YQService\oem\OEMService;
use YQService\oem\traits\Forms;

class Vehicle extends VehicleShort
{
    use Forms;

    public function isPartApplicabilitySupported(): bool
    {
        return $this->getForm('GETPARTAPPLICABILITY_V2') != null;
    }

    public function getDefaultNavigationTree(): CategoryNode
    {
        return OEMService::getService()->getNavigationTree($this->getFilterDataRequest());
    }

    /**
     * @return NavigationLink[]
     */
    public function getNavigationTreeVariants(): array
    {
        $result = [];

        foreach ($this->navigationLinks as $link) {
            if ($link->action == 'getNavigationTree') {
                $result[] = $link;
            }
        }

        return $result;
    }

}