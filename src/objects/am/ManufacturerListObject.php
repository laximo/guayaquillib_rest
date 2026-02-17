<?php

namespace GuayaquilLib\objects\am;

use Exception;
use GuayaquilLib\objects\BaseObject;

class ManufacturerListObject extends BaseObject
{
    /**
     * @var ManufacturerObject[]
     */
    protected $manufacturers = [];

    /**
     * @return ManufacturerObject[]
     */
    public function getManufacturers(): array
    {
        return $this->manufacturers;
    }

    /**
     * @throws Exception
     */
    protected function fromJson($data)
    {
        if (!is_array($data)) {
            return;
        }

        foreach ($this->normalizeList($data) as $row) {
            if (!is_array($row)) {
                continue;
            }
            $this->manufacturers[] = new ManufacturerObject($row);
        }
    }

    /**
     * @param array<mixed> $data
     * @return array<mixed>
     */
    private function normalizeList(array $data): array
    {
        if ($this->isAssoc($data)) {
            return [$data];
        }

        return $data;
    }

    /**
     * @param array<mixed> $value
     * @return bool
     */
    private function isAssoc(array $value): bool
    {
        return array_keys($value) !== range(0, count($value) - 1);
    }
}