<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class PartObject extends BaseObject
{
    /**
     * @var string
     */
    protected $oem;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $codeOnImage;

    /**
     * @var string
     */
    protected $ssd;

    /**
     * var bool
     */
    protected $match;

    /**
     * var bool
     */
    protected $filter;

    /**
     * @var AttributeObject[]
     */
    protected $attributes = [];

    /**
     * @var PartLink[]
     */
    protected $links = [];

    /**
     * @return string
     */
    public function getOem(): string
    {
        return $this->oem;
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
    public function getCodeOnImage(): string
    {
        return $this->codeOnImage;
    }

    /**
     * @return string
     */
    public function getSsd(): string
    {
        return $this->ssd;
    }

    /**
     * @return mixed
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return AttributeObject[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @param array $data
     * @throws Exception
     */
    protected function fromJson(array $data)
    {
        $this->oem = (string)($data['oem'] ?? '');
        $this->name = (string)($data['name'] ?? '');
        $this->codeOnImage = (string)($data['codeOnImage'] ?? $data['codeonimage'] ?? '');
        $this->match = ((string)($data['match'] ?? '')) === 't' || ($data['match'] ?? false) === true;
        $this->filter = (string)($data['filter'] ?? '');
        $this->ssd = (string)($data['ssd'] ?? '');

        $links = $data['links'] ?? $data['Links'] ?? [];
        if (is_array($links)) {
            foreach ($links as $link) {
                $linkData = is_array($link) ? $link : (array)$link;
                if (!empty($linkData)) {
                    $this->links[] = new PartLink($linkData);
                }
            }
        }

        $attrs = $data['attributes'] ?? $data['attribute'] ?? [];
        if (is_array($attrs)) {
            foreach ($attrs as $attribute) {
                $this->attributes[] = new AttributeObject(is_array($attribute) ? $attribute : (array)$attribute);
            }
        }
    }
}