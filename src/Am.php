<?php

namespace GuayaquilLib;

use GuayaquilLib\objects\am\ManufacturerListObject;
use GuayaquilLib\objects\am\ManufacturerObject;
use GuayaquilLib\objects\am\PartListObject;
use GuayaquilLib\objects\am\SecondLevelReplacementList;

class Am
{
    public const optionsCrosses = 'crosses';
    public const optionsImages = 'images';

    public const replacementTypePartOfTheWhole = 'PartOfTheWhole';
    public const replacementTypeReplacement = 'Replacement';

    /**
     * @param string $oem
     * @param string $brand
     * @param string[] $options Array of options
     * @param string[] $replacementTypes Array of replacement types
     * @param string $locale
     * @return Command
     */
    public static function findOem(string $oem, string $brand = '', array $options = [], array $replacementTypes = [], string $locale = 'ru_RU'): Command
    {
        return new Command('findOEM', [
            'Locale' => $locale,
            'Brand' => $brand,
            'OEM' => $oem,
            'ReplacementTypes' => count($replacementTypes) ? implode(',', $replacementTypes) : 'default',
            'Options' => implode(',', $options),
        ], 'am', PartListObject::class, true);
    }

    /**
     * @param int $partId
     * @param string[] $options
     * @param string[] $replacementTypes
     * @param string $locale
     * @return Command
     */
    public static function findPart(int $partId, array $options = [], array $replacementTypes = [], string $locale = 'ru_RU'): Command
    {
        return new Command('findDetail', [
            'Locale' => $locale,
            'DetailId' => $partId,
            'ReplacementTypes' => count($replacementTypes) ? implode(',', $replacementTypes) : 'default',
            'Options' => implode(',', $options),
        ], 'am', PartListObject::class, true);
    }

    public static function listManufacturer(string $locale = 'ru_RU'): Command
    {
        return new Command('listManufacturer', [
            'Locale' => $locale,
        ], 'am', ManufacturerListObject::class, false);
    }

    public static function getManufacturerInfo(int $manufacturerId, string $locale = 'ru_RU'): Command
    {
        return new Command('manufacturerInfo', [
            'Locale' => $locale,
            'ManufacturerId' => $manufacturerId,
        ], 'am', ManufacturerObject::class, false);
    }
}