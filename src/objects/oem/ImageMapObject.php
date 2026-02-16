<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class ImageMapObject extends BaseObject
{
    /**
     * @var MapObject[]
     */
    protected $mapObjects = [];

    /**
     * @return MapObject[]
     */
    public function getMapObjects(): array
    {
        return $this->mapObjects;
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
        foreach ($items as $mapObject) {
            $this->mapObjects[] = new MapObject(is_array($mapObject) ? $mapObject : (array)$mapObject);
        }
    }
}