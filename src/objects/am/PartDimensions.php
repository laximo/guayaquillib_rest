<?php

namespace GuayaquilLib\objects\am;

use GuayaquilLib\objects\BaseObject;

class PartDimensions extends BaseObject
{
    /**
     * @var float
     */
    protected $d1 = 0.0;

    /**
     * @var float
     */
    protected $d2 = 0.0;

    /**
     * @var float
     */
    protected $d3 = 0.0;

    /**
     * @return float
     */
    public function getD1(): float
    {
        return $this->d1;
    }

    /**
     * @return float
     */
    public function getD2(): float
    {
        return $this->d2;
    }

    /**
     * @return float
     */
    public function getD3(): float
    {
        return $this->d3;
    }

    protected function fromJson($data)
    {
        if (!is_string($data) || trim($data) === '') {
            return;
        }

        $parts = preg_split('/\s*[xX]\s*/', trim($data));
        if (!is_array($parts) || count($parts) !== 3) {
            return;
        }

        $this->d1 = (float)$parts[0];
        $this->d2 = (float)$parts[1];
        $this->d3 = (float)$parts[2];
    }
}