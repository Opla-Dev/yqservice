<?php

namespace YQService\oem;


class Request
{
    /**
     * @var string
     */
    private $responseClassName;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $command;

    /**
     * @var array
     */
    private $body;

    /**
     * @var string
     */
    private const path = '/restApi/v2/';

    /**
     * @param string $responseClassName
     * @param string $method
     * @param string $command
     * @param array $body
     */
    public function __construct(string $responseClassName, string $method, string $command, array $body)
    {
        $this->responseClassName = $responseClassName;
        $this->method = $method;
        $this->command = $command;
        $this->body = $body;
    }

    public function getUrl()
    {
        return self::path . $this->getCommand();
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     *
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @param array $body
     *
     */
    public function setBody(array $body)
    {
        $this->body = $body;
    }

    /**
     * @param string $method
     *
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getResponseClassName(): string
    {
        return $this->responseClassName;
    }

    /**
     * @param string $responseClassName
     */
    public function setResponseClassName(string $responseClassName): void
    {
        $this->responseClassName = $responseClassName;
    }


}