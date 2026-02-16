<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class FilterObject extends BaseObject
{
    /**
     * @var FilterFieldObject[]
     */
    protected $fields = [];

    /**
     * @return FilterFieldObject[]
     */
    public function getFields(): array
    {
        return $this->fields;
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
        foreach ($items as $filterField) {
            $this->fields[] = new FilterFieldObject(is_array($filterField) ? $filterField : (array)$filterField);
        }
    }
}