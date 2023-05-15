<?php

namespace YQService\oem\Response;

use Exception;

class Field
{
    public const FIELD_TYPE_INPUT = 'input';
    public const FIELD_TYPE_SELECT = 'select';
    public const FIELD_TYPE_CHECKBOX = 'checkbox';

    /**
     * FIELD_TYPE_* constants
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $value;

    /**
     * @var boolean
     */
    public $required;

    /**
     * @var boolean
     */
    public $hidden; //TODO удалить

    /**
     * @var boolean
     */
    public $readonly;

    public static function classFactory(array $data)
    {
        switch ($data['type']) {
            case Field::FIELD_TYPE_INPUT:
                return new FieldInput();
            case Field::FIELD_TYPE_SELECT:
                return new FieldSelect();
            case Field::FIELD_TYPE_CHECKBOX:
                return new FieldCheckbox();
            default:
                throw new Exception('Unkown field type' . $data['type']);
        }
    }
}