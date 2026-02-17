<?php

namespace GuayaquilLib\objects\am;

use GuayaquilLib\objects\BaseObject;

class ManufacturerObject extends BaseObject
{
    /**
     * @var int
     */
    protected $manufacturerId = 0;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var bool
     */
    protected $isOriginal = false;

    /**
     * @var bool
     */
    protected $searchUrl = false;

    /**
     * @var string[]
     */
    protected $aliases = [];

    /**
     * @return int
     */
    public function getManufacturerId(): int
    {
        return $this->manufacturerId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isOriginal(): bool
    {
        return $this->isOriginal;
    }

    /**
     * @return bool
     */
    public function isSearchUrl(): bool
    {
        return $this->searchUrl;
    }

    /**
     * @return string[]
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    protected function fromJson($data)
    {
        $this->manufacturerId = (int)($data['manufacturerId'] ?? $data['manufacturerid'] ?? 0);
        $this->name = (string)($data['name'] ?? '');
        $this->isOriginal = $this->toBool($data['isOriginal'] ?? $data['isoriginal'] ?? false);
        $this->searchUrl = $this->toBool($data['searchUrl'] ?? $data['searchurl'] ?? false);

        $aliases = $data['alias'] ?? [];
        if (is_string($aliases)) {
            $this->aliases = $aliases !== '' ? array_map('trim', explode(',', $aliases)) : [];
            return;
        }

        if (!is_array($aliases)) {
            $this->aliases = [];
            return;
        }

        $this->aliases = array_values(array_filter(array_map('strval', $aliases), static function (string $alias): bool {
            return $alias !== '';
        }));
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
}