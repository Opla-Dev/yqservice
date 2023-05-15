<?php

namespace YQService\oem\Response;

use YQService\oem\Request\FormDataRequest;

class Form
{
    /**
     * @var string
     */
    public $action;

    /**
     * @var string
     */
    public $updateFormAction;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     * @KeyField
     */
    public $operationName;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $token;

    /**
     * @var Field[]
     */
    public $fields = [];

    public function getFormState(): FormDataRequest
    {
        return new FormDataRequest($this->token);
    }
}