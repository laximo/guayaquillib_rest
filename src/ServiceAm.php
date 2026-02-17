<?php

namespace GuayaquilLib;

use Exception;
use GuayaquilLib\objects\am\ManufacturerListObject;
use GuayaquilLib\objects\am\ManufacturerObject;
use GuayaquilLib\objects\am\PartListObject;
use GuzzleHttp\Client;

class ServiceAm extends Service
{
    public function __construct(
        ?string $login = null,
        ?string $password = null,
        string $baseUrl = 'https://aws.laximo.ru/',
        array  $defaultHeaders = [
            'Accept-Language' => 'ru_RU',
            'accept' => 'application/json',
        ],
        Client $client = null
    )
    {
        parent::__construct($login, $password, $baseUrl, $defaultHeaders, $client);
    }

    /**
     * @param string $oem
     * @param string $brand
     * @param string[] $options
     * @param string[] $replacementtypes
     * @param string $locale
     * @return PartListObject
     * @throws Exception
     */
    public function findOem(string $oem, string $brand = null, bool $returnImages = false, $replacementTypes = false, float $minRate = 0, string $locale = 'ru_RU'): PartListObject
    {
        return $this->executeCommand(Am::findOem($oem, $brand, $returnImages, $replacementTypes, $minRate, $locale));
    }

    /**
     * @param int $partId
     * @param string[] $options
     * @param string[] $replacementtypes
     * @param string $locale
     * @return PartListObject
     * @throws Exception
     */
    public function findPart(int $partId, bool $returnImages, $replacementTypes = false, float $minRate = 0, string $locale = 'ru_RU'): PartListObject
    {
        return $this->executeCommand(Am::findPart($partId, $returnImages, $replacementTypes, $minRate, $locale));
    }

    public function listManufacturer(string $locale = 'ru_RU'): ManufacturerListObject
    {
        return $this->executeCommand(Am::listManufacturer($locale));
    }

    public function getManufacturerInfo(int $manufacturerId, string $locale = 'ru_RU'): ManufacturerObject
    {
        return $this->executeCommand(Am::getManufacturerInfo($manufacturerId, $locale));
    }
}
