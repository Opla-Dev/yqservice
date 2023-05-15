<?php

namespace YQService\oem\exceptions;

use Exception;
use YQService\oem\Request;

class ServiceServerException extends YQException
{

    /**
     * @var string
     */
    private $serviceMessage;

    /**
     * @var Request
     */
    private $request;

    /**
     * @return string
     */
    public function getServiceMessage(): string
    {
        return $this->serviceMessage;
    }

    /**
     * @return Request
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function __construct(?Request $request, $serviceMessage)
    {
        $this->request = $request;
        $this->serviceMessage = $serviceMessage;

        parent::__construct($serviceMessage);
    }
}