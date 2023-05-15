<?php

namespace YQService\oem\Request;

class FormDataRequest
{
    /**
     * @var string
     */
    public $token;

    /**
     * @var FormValue[]
     */
    public $formValues = [];

    /**
     * @param string|null $token
     */
    public function __construct(?string $token = null)
    {
        $this->token = $token;
    }

    public function cancelFieldValue(string $fieldName): FormDataRequest
    {
        return $this->setFieldValue($fieldName, '');
    }

    public function setFieldValue(string $fieldName, string $value): FormDataRequest
    {
        $this->formValues[] = new FormValue($fieldName, $value);

        return $this;
    }

}