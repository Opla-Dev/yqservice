<?php

namespace YQService\oem\Request;

class FilterDataRequest
{
    /**
     * @var string
     */
    public $token;

    /**
     * @var FormValue[]
     */
    public $filterValues = [];

    /**
     * @var string
     */
    public $currentFilterState;

    /**
     * @param string|null $token
     */
    public function __construct(?string $token, ?string $currentFilterState = null)
    {
        $this->token = $token;
        $this->currentFilterState = $currentFilterState;
    }

    public function cancelFilterValue(string $fieldName): FilterDataRequest
    {
        return $this->setFilterValue($fieldName, '');
    }

    public function setFilterValue(string $fieldName, string $value): FilterDataRequest
    {
        $this->filterValues[] = new FormValue($fieldName, $value);

        return $this;
    }

}