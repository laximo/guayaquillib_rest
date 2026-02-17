<?php

namespace GuayaquilLib\objects\am;

use Exception;
use GuayaquilLib\objects\BaseObject;

class PartObject extends BaseObject
{
    /**
     * @var string
     */
    protected $manufacturer = '';

    /**
     * @var int
     */
    protected $manufacturerId = 0;

    /**
     * @var int
     */
    protected $partId = 0;

    /**
     * @var string
     */
    protected $formattedOem = '';

    /**
     * @var string
     */
    protected $oem = '';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var PartCrossObject[]
     */
    protected $replacements = [];

    /**
     * @var PartImage[]
     */
    protected $images = [];

    /**
     * @var float
     */
    protected $weight = 0.0;
    /**
     * @var float
     */
    protected $volume = 0.0;

    /**
     * @var PartDimensions|null
     */
    protected $dimensions;

    /**
     * @return string
     */
    public function getManufacturer(): string
    {
        return $this->manufacturer;
    }

    /**
     * @return int
     */
    public function getManufacturerId(): int
    {
        return $this->manufacturerId;
    }

    /**
     * @return int
     */
    public function getPartId(): int
    {
        return $this->partId;
    }

    /**
     * @return string
     */
    public function getFormattedOem(): string
    {
        return $this->formattedOem;
    }

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
     * @return PartCrossObject[]
     */
    public function getReplacements(): array
    {
        return $this->replacements;
    }

    /**
     * @return PartImage[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @return float
     */
    public function getVolume(): float
    {
        return $this->volume;
    }

    /**
     * @return PartDimensions|null
     */
    public function getDimensions(): ?PartDimensions
    {
        return $this->dimensions;
    }

    /**
     * @param  $data
     * @throws Exception
     */
    protected function fromJson($data)
    {
        if (!is_array($data)) {
            return;
        }

        $this->partId = (int)($data['detailId'] ?? $data['detailid'] ?? 0);
        $this->formattedOem = (string)($data['formattedOem'] ?? $data['formattedoem'] ?? '');
        $this->manufacturer = (string)($data['manufacturer'] ?? '');
        $this->manufacturerId = (int)($data['manufacturerId'] ?? $data['manufacturerid'] ?? 0);
        $this->name = (string)($data['name'] ?? '');
        $this->oem = (string)($data['oem'] ?? '');
        $this->weight = (float)($data['weight'] ?? 0);
        $this->volume = (float)($data['volume'] ?? 0);

        $dimensions = $data['dimensions'] ?? null;
        $this->dimensions = is_string($dimensions) && trim($dimensions) !== ''
            ? new PartDimensions($dimensions)
            : null;

        foreach ($this->normalizeList($data['images'] ?? [], 'image') as $image) {
            if (is_array($image)) {
                $this->images[] = new PartImage($image);
            }
        }

        foreach ($this->normalizeList($data['replacements'] ?? [], 'replacement') as $replacement) {
            if (is_array($replacement)) {
                $this->replacements[] = new PartCrossObject($replacement);
            }
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function toBool($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int)$value === 1;
        }

        if (is_string($value)) {
            return in_array(strtolower($value), ['1', 'true', 'yes', 'on'], true);
        }

        return false;
    }

    /**
     * @param mixed $value
     * @param string $legacyKey
     * @return array<mixed>
     */
    private function normalizeList($value, string $legacyKey = ''): array
    {
        if (!is_array($value)) {
            return [];
        }

        if ($legacyKey !== '' && array_key_exists($legacyKey, $value) && is_array($value[$legacyKey])) {
            $value = $value[$legacyKey];
        }

        if ($value === []) {
            return [];
        }

        if ($this->isAssoc($value)) {
            return [$value];
        }

        return $value;
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