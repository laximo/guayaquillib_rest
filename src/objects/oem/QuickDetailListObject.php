<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class QuickDetailListObject extends BaseObject
{
    /**
     * @var CategoryObject[]
     */
    protected $categories = [];

    /**
     * @return CategoryObject[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @throws Exception
     */
    protected function fromJson(array $data)
    {
        $items = $data['categories'] ?? $data['Category'] ?? $data;
        if (!is_array($items)) {
            return;
        }
        foreach ($items as $category) {
            $this->categories[] = new CategoryObject(is_array($category) ? $category : (array)$category);
        }
    }
}