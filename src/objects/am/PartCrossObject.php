<?php

namespace GuayaquilLib\objects\am;

use Exception;
use GuayaquilLib\objects\BaseObject;

class PartCrossObject extends BaseObject
{
    /**
     * @var float
     */
    protected $rate = 0.0;

    /**
     * @var string
     */
    protected $type = '';

    /**
     * @var string
     */
    protected $way = '';

    /**
     * @var PartObject
     */
    protected $part;

    /**
     * @var int
     */
    protected $replacementId = 0;

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
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
    public function getWay(): string
    {
        return $this->way;
    }

    /**
     * @return PartObject
     */
    public function getPart(): PartObject
    {
        return $this->part;
    }

    /**
     * @return int
     */
    public function getReplacementId(): int
    {
        return $this->replacementId;
    }

    /**
     * @throws Exception
     */
    protected function fromJson($data)
    {
        $this->rate = (float)($data['rate'] ?? 0);
        $this->type = (string)($data['type'] ?? '');
        $this->way = (string)($data['way'] ?? '');
        $this->replacementId = (int)($data['replacementId'] ?? $data['replacementid'] ?? 0);

        $detail = $data['detail'] ?? [];
        if (!is_array($detail)) {
            $detail = [];
        }

        $this->part = new PartObject($detail);
    }
}