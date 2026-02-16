<?php

namespace GuayaquilLib\objects\oem;

use GuayaquilLib\objects\BaseObject;

class FilterFieldValueObject extends BaseObject
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $note;

    /**
     * @var string
     */
    protected $ssdModification;

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
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @return string
     */
    public function getSsdModification(): string
    {
        return $this->ssdModification;
    }

    protected function fromJson(array $data)
    {
        $this->name = (string)($data['name'] ?? '');
        $this->note = (string)($data['note'] ?? '');
        $this->ssdModification = (string)($data['ssdModification'] ?? $data['ssdmodification'] ?? '');
    }
}