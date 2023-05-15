<?php

namespace YQService\oem\traits;

use YQService\oem\Response\Form;

trait Forms
{
    /**
     * @var Form[]
     */
    public $forms = [];

    public function getForm(string $formAction): ?Form
    {
        return array_key_exists($formAction, $this->forms) ? $this->forms[$formAction] : null;
    }
}