<?php

namespace YQService\oem\Response;

class AttrNode
{
    const OEM_SERVICE_ATTRIBUTE_SIMPLE_TYPE = 'simple';
    const OEM_SERVICE_ATTRIBUTE_SIMPLE_VALUE_TYPE = 'simpleValue';
    const OEM_SERVICE_ATTRIBUTE_COMPOSITE_TYPE = 'composite';
    const OEM_SERVICE_ATTRIBUTE_GROUP_TYPE = 'group';

    /**
     * @var string
     * @KeyField
     */
    public $code;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string[]
     */
    public $values = [];

    /**
     * @var AttrNode[]
     */
    public $children = [];

    /**
     * @var string
     */
    public $description;

    /**
     * simple, simpleValue, composite, group
     * @var string
     */
    public $type;
}