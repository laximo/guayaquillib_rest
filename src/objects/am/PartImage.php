<?php

namespace GuayaquilLib\objects\am;

use GuayaquilLib\objects\BaseObject;

class PartImage extends BaseObject
{
    /**
     * @var string
     */
    protected $filename = '';

    /**
     * @var int
     */
    protected $height = 0;

    /**
     * @var int
     */
    protected $width = 0;

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    protected function fromJson($data)
    {
        $this->filename = (string)($data['fileName'] ?? $data['filename'] ?? '');
        $this->height = (int)($data['height'] ?? 0);
        $this->width = (int)($data['width'] ?? 0);
    }
}