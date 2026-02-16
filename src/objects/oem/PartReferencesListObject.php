<?php

namespace GuayaquilLib\objects\oem;


use Exception;
use GuayaquilLib\objects\BaseObject;

class PartReferencesListObject extends BaseObject
{
    /**
     * @var string $oem
     */
    protected $oem;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var PartReferenceObject[] $referencesList
     */
    protected $references = [];

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
     * @return PartReferenceObject[]
     */
    public function getReferences(): array
    {
        return $this->references;
    }

    /**
     * @param array $data
     * @throws Exception
     */
    protected function fromJson(array $data)
    {
        if (empty($data)) {
            return;
        }
        $ref = (isset($data[0]) && is_array($data[0])) ? $data[0] : $data;
        $this->oem = (string)($ref['oem'] ?? '');
        $this->name = (string)($ref['name'] ?? '');
        $catalogs = $ref['catalogs'] ?? [];
        if (is_array($catalogs)) {
            foreach ($catalogs as $catalog) {
                $this->references[] = new PartReferenceObject(is_array($catalog) ? $catalog : (array)$catalog);
            }
        }
    }
}