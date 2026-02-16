<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class GroupObject extends BaseObject
{

    /**
     * @var string
     */
    protected $contains;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $synonyms;

    /**
     * @var string
     */
    protected $quickGroupId;

    /**
     * @var bool
     */
    protected $searchable;

    /**
     * @var GroupObject[]
     */
    protected $childGroups = [];

    /**
     * @return string
     */
    public function getContains(): string
    {
        return $this->contains;
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
    public function getSynonyms(): string
    {
        return $this->synonyms;
    }

    /**
     * @return string
     */
    public function getQuickGroupId(): string
    {
        return $this->quickGroupId;
    }

    /**
     * @return bool
     */
    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * @return GroupObject[]
     */
    public function getChildGroups(): array
    {
        return $this->childGroups;
    }

    /**
     * @param array $data
     * @throws Exception
     */
    protected function fromJson(array $data)
    {
        $this->contains = (string)($data['contains'] ?? '');
        $this->searchable = ($data['link'] ?? '') === true || (string)($data['link'] ?? '') === 'true';
        $this->name = (string)($data['name'] ?? '');
        $this->quickGroupId = (string)($data['quickGroupId'] ?? $data['quickgroupid'] ?? '');
        $this->synonyms = (string)($data['synonyms'] ?? '');

        $children = $data['children'] ?? $data['row'] ?? [];
        if (is_array($children)) {
            foreach ($children as $child) {
                $this->childGroups[] = new GroupObject(is_array($child) ? $child : (array)$child);
            }
        }
    }
}