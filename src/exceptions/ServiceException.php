<?php

namespace YQService\oem\exceptions;

use Exception;

class ServiceException extends YQException
{

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    private $requestId;

    public function __construct($message, $requestId)
    {
        $this->message = $message;
        $this->requestId = $requestId;

        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }
}