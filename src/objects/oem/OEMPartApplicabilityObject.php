<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class OEMPartApplicabilityObject extends BaseObject
{
    public const APPLICABILITY_NONAPPLICABLE = 'NONAPPLICABLE';
    public const APPLICABILITY_PARTIAL = 'PARTIAL';
    public const APPLICABILITY_FULLY = 'FULLY';

    /**
     * @var string
     */
    protected $applicability;

    /**
     * @var CategoryObject[]
     */
    protected $categories = [];

    /**
     * @return string
     */
    public function getApplicability(): string
    {
        return $this->applicability;
    }

    /**
     * @return CategoryObject[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param array $data
     * @throws Exception
     */
    protected function fromJson(array $data)
    {
        $this->applicability = (string)($data['applicability'] ?? '');

        $categories = $data['categories'] ?? [];
        if (is_array($categories)) {
            foreach ($categories as $category) {
                $this->categories[] = new CategoryObject(is_array($category) ? $category : (array)$category);
            }
        }
    }
}
