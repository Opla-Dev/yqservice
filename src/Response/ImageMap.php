<?php

namespace YQService\oem\Response;

class ImageMap
{
    public const IMAGE_SIZE_250 = '250';
    public const IMAGE_SIZE_225 = '225';
    public const IMAGE_SIZE_200 = '200';
    public const IMAGE_SIZE_175 = '175';
    public const IMAGE_SIZE_150 = '150';
    public const IMAGE_SIZE_FULL = 'source';

    /**
     * @var string
     */
    public $imageName;

    /**
     * @var ImageMapArea[]
     */
    public $areas = [];

    /**
     * @param string $size IMAGE_SIZE_* constants
     * @return string
     */
    public function getImageNames(string $size = ''): string
    {
        return str_replace('%size%', $size, $this->imageName);
    }
}