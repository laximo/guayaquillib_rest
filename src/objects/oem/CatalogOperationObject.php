<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class CatalogOperationObject extends BaseObject
{

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $kind;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var CatalogOperationFieldObject[]
     */
    protected $fields = [];

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getKind(): string
    {
        return $this->kind;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return CatalogOperationFieldObject[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param array $data
     * @throws Exception
     */
    protected function fromJson(array $data)
    {
        $this->description = (string)($data['description'] ?? '');
        $this->kind = (string)($data['kind'] ?? '');
        $this->name = (string)($data['name'] ?? '');
        $fieldList = $data['fields'] ?? $data['field'] ?? [];
        if (is_array($fieldList)) {
            foreach ($fieldList as $field) {
                $this->fields[] = new CatalogOperationFieldObject(is_array($field) ? $field : (array)$field);
            }
        }
    }
}