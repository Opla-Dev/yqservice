<?php

namespace YQService\oem\Request;

class FilterStatefullFormDataRequest extends FilterDataRequest //TODO test it
{
    /**
     * @var FormValue[]
     */
    public $formValues = [];

    public function cancelFieldValue(string $fieldName): FilterStatefullFormDataRequest
    {
        return $this->setFieldValue($fieldName, '');
    }

    public function setFieldValue(string $fieldName, string $value): FilterStatefullFormDataRequest
    {
        $this->formValues[] = new FormValue($fieldName, $value);

        return $this;
    }

}