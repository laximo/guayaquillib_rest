<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class CategoryListObject extends BaseObject
{
    /**
     * @var CategoryObject[]
     */
    protected $root = [];

    /**
     * @return CategoryObject[]
     */
    public function getRoot(): array
    {
        return $this->root;
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
        $categories = [];
        foreach ($items as $categoryData) {
            $category = new CategoryObject(is_array($categoryData) ? $categoryData : (array)$categoryData);
            $categories[$category->getCategoryId()] = $category;
        }

        foreach ($categories as $category) {
            $parentId = $category->getParentCategoryId();

            if ($parentId !== null && isset($categories[$parentId])) {
                $categories[$parentId]->appendChildren($category);
            } else {
                $this->root[] = $category;
            }
        }
    }
}