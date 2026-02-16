<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class VehicleObject extends BaseObject
{
    /**
     * @var string
     */
    protected $catalog;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $ssd;

    /**
     * @var string
     */
    protected $vehicleId;

    /**
     * @var AttributeObject[]
     */
    protected $attributes = [];

    /**
     * @var string[]
     */
    protected $systemProperties = [];
    /**
     * @var string
     */
    protected $brand;

    /**
     * @return string
     */
    public function getCatalog(): string
    {
        return $this->catalog;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSsd(): string
    {
        return $this->ssd;
    }

    /**
     * @return string
     */
    public function getVehicleId(): string
    {
        return $this->vehicleId;
    }

    /**
     * @return AttributeObject[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return string[]
     */
    public function getSystemProperties(): array
    {
        return $this->systemProperties;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @param array $data
     * @throws Exception
     */
    protected function fromJson(array $data)
    {
        $this->catalog = (string)($data['catalog'] ?? '');
        $this->name = (string)($data['name'] ?? '');
        $this->ssd = (string)($data['ssd'] ?? '');
        $this->vehicleId = (string)($data['vehicleId'] ?? $data['vehicleid'] ?? '');
        $this->brand = (string)($data['brand'] ?? '');

        $attrs = $data['attributes'] ?? $data['attribute'] ?? [];
        if (is_array($attrs)) {
            foreach ($attrs as $attribute) {
                $attrData = is_array($attribute) ? $attribute : (array)$attribute;
                $attributeObject = new AttributeObject($attrData);
                $this->attributes[$attributeObject->getKey()] = $attributeObject;
            }
        }

        $sysProps = $data['sysProperties'] ?? $data['sysproperty'] ?? [];
        if (is_array($sysProps)) {
            foreach ($sysProps as $key => $val) {
                if (is_numeric($key) && is_array($val) && isset($val['key'])) {
                    $this->systemProperties[(string)$val['key']] = (string)($val['value'] ?? $val);
                } else {
                    $this->systemProperties[(string)$key] = (string)$val;
                }
            }
        }
    }
}