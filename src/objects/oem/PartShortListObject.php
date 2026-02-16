<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class PartShortListObject extends BaseObject
{
    /**
     * @var PartShortObject[]
     */
    protected $parts = [];

    /**
     * @return PartShortObject[]
     */
    public function getParts(): array
    {
        return $this->parts;
    }

    /**
     * @throws Exception
     */
    protected function fromJson(array $data)
    {
        $items = $data['row'] ?? $data;
        if (!is_array($items)) {
            return;
        }
        foreach ($items as $part) {
            $this->parts[] = new PartShortObject(is_array($part) ? $part : (array)$part);
        }
    }
}