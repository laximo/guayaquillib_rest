<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class WizardObject extends BaseObject
{
    /**
     * @var WizardStepObject[]
     */
    protected $steps = [];

    /**
     * @return WizardStepObject[]
     */
    public function getSteps(): array
    {
        return $this->steps;
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
        foreach ($items as $step) {
            $this->steps[] = new WizardStepObject(is_array($step) ? $step : (array)$step);
        }
    }
}