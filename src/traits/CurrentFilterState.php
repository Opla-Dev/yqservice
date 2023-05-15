<?php

namespace YQService\oem\traits;

use YQService\oem\Request\FilterDataRequest;

trait CurrentFilterState
{
    use Token;

    public $currentFilterState;

    /**
     * @param string[] $params
     */
    public function __construct(array $params)
    {
        $this->currentFilterState = $params['currentFilterState'];
    }

    public function getFilterDataRequest(string $token = null): FilterDataRequest
    {
        return new FilterDataRequest($token ?: $this->token, $this->currentFilterState);
    }
}