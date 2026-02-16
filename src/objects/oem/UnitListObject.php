<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class UnitListObject extends BaseObject
{

    /**
     * @var UnitObject[]
     */
    protected $units = [];

    /**
     * @return UnitObject[]
     */
    public function getUnits(): array
    {
        return $this->units;
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
        foreach ($items as $unit) {
            $this->units[] = new UnitObject(is_array($unit) ? $unit : (array)$unit);
        }
    }
}