<?php

namespace GuayaquilLib;

use GuayaquilLib\objects\am\ManufacturerListObject;
use GuayaquilLib\objects\am\ManufacturerObject;
use GuayaquilLib\objects\am\PartListObject;

class Am
{
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
    public static function findOem(string $oem, string $brand = null, bool $returnImages, $replacementTypes = false, float $minRate = 0, string $locale = 'ru_RU'): Command
    {
        $params = [
            'Accept-Language' => $locale,
            'oem' => $oem,
            'showImages' => $returnImages,
            'minRate' => $minRate,
        ];

        if ($brand !== '' && isset($brand)) {
            $params['brand'] = $brand;
        }

        if ($replacementTypes === true) {
            $params['replacementTypes'] = [
                self::replacementTypePartOfTheWhole,
                self::replacementTypeReplacement,
            ];
        } elseif (is_array($replacementTypes) && count($replacementTypes) > 0) {
            $params['replacementTypes'] = $replacementTypes;
        }

        return new Command('findOem', $params, 'am', PartListObject::class, true);
    }

    /**
     * @param int $partId
     * @param string[] $options
     * @param string[] $replacementTypes
     * @param string $locale
     * @return Command
     */
    public static function findPart(int $partId, bool $returnImages, $replacementTypes = false, float $minRate = 0, string $locale = 'ru_RU'): Command
    {
        $params = [
            'Accept-Language' => $locale,
            'detailId' => $partId,
            'showImages' => $returnImages,
            'minRate' => $minRate,
        ];

        if ($replacementTypes === true) {
            $params['replacementTypes'] = [
                self::replacementTypePartOfTheWhole,
                self::replacementTypeReplacement,
            ];
        } elseif (is_array($replacementTypes) && count($replacementTypes) > 0) {
            $params['replacementTypes'] = $replacementTypes;
        }

        return new Command('findDetail', $params, 'am', PartListObject::class, true);
    }

    public static function listManufacturer(string $locale = 'ru_RU'): Command
    {
        return new Command('listManufacturer', [
            'Accept-Language' => $locale,
        ], 'am', ManufacturerListObject::class, false);
    }

    public static function getManufacturerInfo(int $manufacturerId, string $locale = 'ru_RU'): Command
    {
        return new Command('manufacturerInfo', [
            'Accept-Language' => $locale,
            'manufacturerId' => $manufacturerId,
        ], 'am', ManufacturerObject::class, false);
    }
}