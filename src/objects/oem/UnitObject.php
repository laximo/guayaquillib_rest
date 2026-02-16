<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class UnitObject extends BaseObject
{

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $unitId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $imageUrl;

    /**
     * @var string
     */
    protected $ssd;

    /**
     * @var string
     */
    protected $filter;

    /**
     * @var PartObject[]
     */
    protected $parts = [];

    /**
     * @var AttributeObject[];
     */
    protected $attributes = [];

    public function getLargeImageUrl(): string
    {
        return $this->getImageUrl('source');
    }

    /**
     * @param int $size can be 150, 175, 200, 225, 250
     * @return string
     */
    public function getImageUrl($size = 150): string
    {
        return str_replace('%size%', (string)$size, $this->imageUrl);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getUnitId(): string
    {
        return $this->unitId;
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
    public function getFilter(): string
    {
        return $this->filter;
    }

    /**
     * @return PartObject[]
     */
    public function getParts(): array
    {
        return $this->parts;
    }

    /**
     * @return AttributeObject[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @throws Exception
     */
    protected function fromJson(array $data)
    {
        $this->code = (string)($data['code'] ?? '');
        $this->unitId = (string)($data['unitId'] ?? $data['unitid'] ?? '');
        $this->name = (string)($data['name'] ?? '');
        $this->imageUrl = str_replace('http://', 'https://', (string)($data['imageUrl'] ?? $data['imageurl'] ?? ''));
        $this->ssd = (string)($data['ssd'] ?? '');
        $this->filter = (string)($data['filter'] ?? '');

        if (isset($data['attributes']) && is_array($data['attributes'])) {
            foreach ($data['attributes'] as $attribute) {
                $this->attributes[] = new AttributeObject(is_array($attribute) ? $attribute : (array)$attribute);
            }
        }

        if (isset($data['details']) && is_array($data['details'])) {
            foreach ($data['details'] as $part) {
                $this->parts[] = new PartObject(is_array($part) ? $part : (array)$part);
            }
        }
    }
}