<?php

namespace YQService\oem;


class Config
{
    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $serviceUrl = 'https://oem-api.yqservice.eu';

    /**
     * @var string
     */
    private $acceptLanguage = 'en-US';

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * Config constructor.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->setParams($params);
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getServiceUrl()
    {
        return $this->serviceUrl;
    }

    /**
     * @param string $serviceUrl
     */
    public function setServiceUrl($serviceUrl)
    {
        $this->serviceUrl = $serviceUrl;
    }

    /**
     * @return string
     */
    public function getAcceptLanguage(): string
    {
        return $this->acceptLanguage;
    }

    /**
     * @param string $acceptLanguage
     */
    public function setAcceptLanguage(string $acceptLanguage): void
    {
        $this->acceptLanguage = $acceptLanguage;
    }

    private function setParams($params)
    {
        foreach ($params as $name => $param) {
            $this->{'set' . $name}($param);
        }
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     */
    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

}