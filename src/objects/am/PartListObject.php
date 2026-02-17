<?php

namespace GuayaquilLib\objects\am;


use Exception;
use GuayaquilLib\objects\BaseObject;

class PartListObject extends BaseObject
{
    /**
     * @var PartObject[] $oems
     */
    protected $oems = [];

    /**
     * @return PartObject[]
     */
    public function getOems(): array
    {
        return $this->oems;
    }

    /**
     * @param $data
     * @throws Exception
     */
    protected function fromJson($data)
    {
        if (!is_array($data)) {
            return;
        }

        foreach ($this->normalizeList($data) as $detail) {
            if (!is_array($detail)) {
                continue;
            }
            $detail = new PartObject($detail);
            $this->oems[] = $detail;
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