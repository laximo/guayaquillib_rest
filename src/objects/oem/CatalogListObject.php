<?php

namespace GuayaquilLib\objects\oem;

use Exception;
use GuayaquilLib\objects\BaseObject;

class CatalogListObject extends BaseObject
{
    /**
     * @var CatalogObject[]
     */
    protected $catalogs = [];

    /**
     * @var string[]
     */
    protected $examples;

    /**
     * @return CatalogObject[]
     */
    public function getCatalogs(): array
    {
        return $this->catalogs;
    }

    /**
     * @return mixed
     */
    public function getExamples(): array
    {
        return $this->examples;
    }


    /**
     * @throws Exception
     */
    protected function fromJson(array $data)
    {
        $items = isset($data[0]) ? $data : ($data['row'] ?? []);
        if (!is_array($items)) {
            $items = [];
        }
        foreach ($items as $catalog) {
            $this->catalogs[] = new CatalogObject(is_array($catalog) ? $catalog : (array)$catalog);
        }

        $this->examples = $this->getRandomExample();
    }

    private function getRandomExample(): array
    {
        if (!$this->catalogs) {
            $this->catalogs = [];
        }

        $rand = rand(1, count($this->catalogs));

        $count = 0;

        $vinExample = 'WAUZZZ4M6JD010702';
        $frameExample = 'XZU423-0001026';

        foreach ($this->catalogs as $catalog) {
            $count++;

            if ($count === $rand && $catalog instanceof CatalogObject) {
                $vinFeature = $catalog->getVinSearchFeature();
                if ($vinFeature && $vinFeature->getExample()) {
                    $vinExample = $vinFeature->getExample();
                }
                break;
            }
        }

        $count = 0;
        $rand = rand(1, count($this->catalogs));

        foreach ($this->catalogs as $catalog) {
            $count++;

            if ($count === $rand && $catalog instanceof CatalogObject) {
                $frameFeature = $catalog->getFrameSearchFeature();
                if ($frameFeature && $frameFeature->getExample()) {
                    $frameExample = $frameFeature->getExample();
                }
                break;
            }
        }

        return [$vinExample, $frameExample];
    }
}