<?php

namespace GuayaquilLib\objects\oem;

use GuayaquilLib\objects\BaseObject;

class PartLink extends BaseObject
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $command;

    /**
     * @var string
     */
    protected $catalog;

    /**
     * @var string
     */
    protected $categoryId;

    /**
     * @var bool
     */
    private $localized;

    /**
     * @var string
     */
    private $vehicleId;

    /**
     * @var string
     */
    private $ssd;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

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
    public function getCategoryId(): string
    {
        return $this->categoryId;
    }

    /**
     * @return bool
     */
    public function isLocalized(): bool
    {
        return $this->localized;
    }

    /**
     * @return string
     */
    public function getVehicleId(): string
    {
        return $this->vehicleId;
    }

    /**
     * @return string
     */
    public function getSsd(): string
    {
        return $this->ssd;
    }

    /**
     * @param array $data
     */
    protected function fromJson(array $data)
    {
        $this->catalog = (string)($data['catalog'] ?? $data['Catalog'] ?? '');
        $this->categoryId = (string)($data['categoryId'] ?? $data['CategoryId'] ?? '');
        $this->command = (string)($data['command'] ?? $data['Command'] ?? '');
        $this->localized = ($data['localized'] ?? $data['Localized'] ?? '') === true
            || (string)($data['localized'] ?? $data['Localized'] ?? '') === 'true';
        $this->vehicleId = (string)($data['vehicleId'] ?? $data['VehicleId'] ?? '');
        $this->label = (string)($data['label'] ?? '');
        $this->ssd = (string)($data['ssd'] ?? '');
        $this->type = (string)($data['type'] ?? '');
    }
}