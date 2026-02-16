<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class WizardStepObject extends BaseObject
{

    /**
     * @var bool
     */
    protected $allowListVehicles;

    /**
     * @var bool
     */
    protected $automatic;

    /**
     * @var string
     */
    protected $conditionId;

    /**
     * @var bool
     */
    protected $determined;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $ssd;

    /**
     * @var WizardStepOptionObject[]
     */
    protected $options = [];

    /**
     * @return bool
     */
    public function isAllowListVehicles(): bool
    {
        return $this->allowListVehicles;
    }

    /**
     * @return bool
     */
    public function isAutomatic(): bool
    {
        return $this->automatic;
    }

    /**
     * @return string
     */
    public function getConditionId(): string
    {
        return $this->conditionId;
    }

    /**
     * @return bool
     */
    public function isDetermined(): bool
    {
        return $this->determined;
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getSsd(): string
    {
        return $this->ssd;
    }

    /**
     * @return WizardStepOptionObject[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }


    /**
     * @throws Exception
     */
    protected function fromJson(array $data)
    {
        $this->allowListVehicles = ($data['allowListVehicles'] ?? $data['allowlistvehicles'] ?? '') === true
            || (string)($data['allowListVehicles'] ?? $data['allowlistvehicles'] ?? '') === 'true';
        $this->automatic = ($data['automatic'] ?? '') === true
            || (string)($data['automatic'] ?? '') === 'true';
        $this->conditionId = (string)($data['conditionId'] ?? $data['conditionid'] ?? '');
        $this->determined = ($data['determined'] ?? '') === true
            || (string)($data['determined'] ?? '') === 'true';
        $this->name = (string)($data['name'] ?? '');
        $this->type = (string)($data['type'] ?? '');
        $this->value = (string)($data['value'] ?? '');
        $this->ssd = (string)($data['ssd'] ?? '');

        $opts = $data['options'] ?? [];
        if (is_array($opts)) {
            foreach ($opts as $option) {
                $optData = is_array($option) ? $option : (array)$option;
                if (isset($optData['key']) || isset($optData['value'])) {
                    $this->options[] = new WizardStepOptionObject($optData);
                }
            }
        }
    }
}