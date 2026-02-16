<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class PartListObject extends BaseObject
{

    /**
     * @var PartObject[]
     */
    protected $parts = [];

    /**
     * @return PartObject[]
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
            $this->parts[] = new PartObject(is_array($part) ? $part : (array)$part);
        }
    }
}