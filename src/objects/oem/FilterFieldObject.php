<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class FilterFieldObject extends BaseObject
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var FilterFieldValueObject[]
     */
    protected $values = [];

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
     * @return FilterFieldValueObject[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @throws Exception
     */
    protected function fromJson(array $data)
    {
        $this->name = (string)($data['name'] ?? '');
        $this->type = (string)($data['type'] ?? '');

        $vals = $data['values'] ?? [];
        if (is_array($vals)) {
            foreach ($vals as $value) {
                $this->values[] = new FilterFieldValueObject(is_array($value) ? $value : (array)$value);
            }
        }
    }
}