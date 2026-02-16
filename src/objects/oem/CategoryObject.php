<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class CategoryObject extends BaseObject
{
    /**
     * @var int
     */
    protected $categoryId;

    /**
     * @var CategoryObject[]|null
     */
    protected $childrens = [];

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $parentCategoryId;

    /**
     * @var string
     */
    protected $ssd;

    /**
     * @var UnitObject[]
     */
    protected $units = [];

    /**
     * @return int|string
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @return CategoryObject[]|null
     */
    public function getChildrens(): ?array
    {
        return $this->childrens;
    }

    /**
     * @param CategoryObject $child
     * @return void
     */
    public function appendChildren(CategoryObject $child): void
    {
        $this->childrens[] = $child;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int|string|null
     */
    public function getParentCategoryId()
    {
        return $this->parentCategoryId;
    }

    /**
     * @return string
     */
    public function getSsd(): string
    {
        return $this->ssd;
    }

    /**
     * @return UnitObject[]
     */
    public function getUnits(): array
    {
        return $this->units;
    }

    /**
     * @param array $data
     * @throws Exception
     */
    protected function fromJson(array $data)
    {
        $this->categoryId = (int)($data['categoryId'] ?? $data['categoryid'] ?? 0);
        $this->code = (string)($data['code'] ?? '');
        $this->name = (string)($data['name'] ?? '');
        $this->parentCategoryId = isset($data['parentCategoryId']) || isset($data['parentcategoryid'])
            ? (int)($data['parentCategoryId'] ?? $data['parentcategoryid']) : null;
        $this->ssd = (string)($data['ssd'] ?? '');

        if (isset($data['units']) && is_array($data['units'])) {
            foreach ($data['units'] as $unit) {
                $this->units[] = new UnitObject(is_array($unit) ? $unit : (array)$unit);
            }
        }
    }
}