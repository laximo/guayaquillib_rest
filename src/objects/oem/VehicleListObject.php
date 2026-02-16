<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class VehicleListObject extends BaseObject
{
    /**
     * @var VehicleObject[]
     */
    protected $vehicles = [];

    /**
     * @return VehicleObject[]
     */
    public function getVehicles(): array
    {
        return $this->vehicles;
    }

    /**
     * @param array $data
     * @throws Exception
     */
    protected function fromJson(array $data)
    {
        $items = $data['row'] ?? $data;
        if (!is_array($items)) {
            return;
        }
        foreach ($items as $vehicle) {
            $this->vehicles[] = new VehicleObject(is_array($vehicle) ? $vehicle : (array)$vehicle);
        }
    }
}