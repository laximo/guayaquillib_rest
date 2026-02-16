<?php

namespace GuayaquilLib;

use GuayaquilLib\objects\oem\CatalogListObject;
use GuayaquilLib\objects\oem\CatalogObject;
use GuayaquilLib\objects\oem\CategoryListObject;
use GuayaquilLib\objects\oem\FilterObject;
use GuayaquilLib\objects\oem\GroupObject;
use GuayaquilLib\objects\oem\ImageMapObject;
use GuayaquilLib\objects\oem\OEMPartApplicabilityObject;
use GuayaquilLib\objects\oem\PartListObject;
use GuayaquilLib\objects\oem\PartReferencesListObject;
use GuayaquilLib\objects\oem\PartShortListObject;
use GuayaquilLib\objects\oem\QuickDetailListObject;
use GuayaquilLib\objects\oem\UnitListObject;
use GuayaquilLib\objects\oem\UnitObject;
use GuayaquilLib\objects\oem\VehicleListObject;
use GuayaquilLib\objects\oem\VehicleObject;
use GuayaquilLib\objects\oem\WizardObject;

class Oem
{
    public static function listCatalogs(string $locale = 'ru_RU'): Command
    {
        return new Command('listCatalogs', [
            'Accept-Language' => $locale
        ], 'oem', CatalogListObject::class, true);
    }

    public static function getCatalogInfo(string $catalog, string $locale = 'ru_RU', bool $withPermissions = false): Command
    {
        $params = [
            'Accept-Language' => $locale,
            'catalog' => $catalog
        ];
        if ($withPermissions) {
            $params['withPermissions'] = 'true';
        }

        return new Command('getCatalogInfo', $params, 'oem', CatalogObject::class, false);
    }

    public static function findVehicle(string $identString, string $locale = 'ru_RU'): Command
    {
        return new Command('findVehicle', [
            'Accept-Language' => $locale,
            'identString' => $identString
        ], 'oem', VehicleListObject::class, true);
    }

    public static function findVehicleByPlateNumber(string $plate, string $locale = 'ru_RU'): Command
    {
        return new Command('findVehicleByPlateNumber', [
            'Accept-Language' => $locale,
            'plateNumber' => $plate,
            'countryCode' => 'ru',
        ], 'oem', VehicleListObject::class, true);
    }

    public static function findVehicleByWizard2(string $catalog, string $ssd, string $locale = 'ru_RU'): Command
    {
        return new Command('findVehicleByWizard2', [
            'Accept-Language' => $locale,
            'catalog' => $catalog,
            'ssd' => $ssd,
        ], 'oem', VehicleListObject::class, true);
    }

    public static function execCustomOperation(string $catalog, string $operation, array $data, string $locale = 'ru_RU'): Command
    {
        return new Command('execCustomOperation', array_merge([
            'Accept-Language' => $locale,
            'catalog' => $catalog,
            'operation' => $operation
        ], $data), 'oem', VehicleListObject::class, true);
    }

    public static function getVehicleInfo(string $catalog, string $vehicleId, string $ssd, string $locale = 'ru_RU'): Command
    {
        return new Command('getVehicleInfo', [
            'Accept-Language' => $locale,
            'catalog' => $catalog,
            'vehicleId' => $vehicleId,
            'ssd' => $ssd,
        ], 'oem', VehicleObject::class, false);
    }

    public static function getWizard2(string $catalog, $ssd = '', string $locale = 'ru_RU'): Command
    {
        return new Command('getWizard2', [
            'Accept-Language' => $locale,
            'catalog' => $catalog,
            'ssd' => $ssd
        ], 'oem', WizardObject::class, true);
    }

    public static function listCategories(string $catalog, string $vehicleId, string $ssd, string $categoryId = '-1', string $locale = 'ru_RU'): Command
    {
        return new Command('listCategories', [
            'Accept-Language' => $locale,
            'catalog' => $catalog,
            'vehicleId' => $vehicleId,
            'categoryId' => $categoryId,
            'ssd' => $ssd,
        ], 'oem', CategoryListObject::class, true);
    }

    public static function listUnits(string $catalog, string $vehicleId, string $ssd, string $categoryId = '-1', string $locale = 'ru_RU'): Command
    {
        return new Command('listUnits', [
            'Accept-Language' => $locale,
            'catalog' => $catalog,
            'vehicleId' => $vehicleId,
            'categoryId' => $categoryId,
            'ssd' => $ssd,
        ], 'oem', UnitListObject::class, true);
    }

    public static function getUnitInfo(string $catalog, string $ssd, string $unitId, string $locale = 'ru_RU'): Command
    {
        return new Command('getUnitInfo', [
            'Accept-Language' => $locale,
            'catalog' => $catalog,
            'unitId' => $unitId,
            'ssd' => $ssd,
        ], 'oem', UnitObject::class, false);
    }

    public static function listImageMapByUnit(string $catalog, string $ssd, string $unitId): Command
    {
        return new Command('listImageMapByUnit', [
            'catalog' => $catalog,
            'unitId' => $unitId,
            'ssd' => $ssd,
        ], 'oem', ImageMapObject::class, true);
    }

    public static function listPartsByUnit(string $catalog, string $ssd, string $unitId, string $locale = 'ru_RU'): Command
    {
        return new Command('listDetailByUnit', [
            'Accept-Language' => $locale,
            'catalog' => $catalog,
            'unitId' => $unitId,
            'ssd' => $ssd,
        ], 'oem', PartListObject::class, true);
    }

    public static function getFilterByUnit(string $catalog, string $vehicleId, string $ssd, string $unitId, string $filter, string $locale = 'ru_RU'): Command
    {
        return new Command('getFilterByUnit', [
            'Accept-Language' => $locale,
            'catalog' => $catalog,
            'filter' => $filter,
            'vehicleId' => $vehicleId,
            'unitId' => $unitId,
            'ssd' => $ssd
        ], 'oem', FilterObject::class, true);
    }

    public static function getFilterByPart(string $catalog, string $vehicleId, string $ssd, string $unitId, string $partId, string $filter, string $locale = 'ru_RU'): Command
    {
        return new Command('getFilterByDetail', [
            'Accept-Language' => $locale,
            'catalog' => $catalog,
            'filter' => $filter,
            'vehicleId' => $vehicleId,
            'unitId' => $unitId,
            'detailId' => $partId,
            'ssd' => $ssd
        ], 'oem', FilterObject::class, true);
    }

    public static function listQuickGroup(string $catalog, string $vehicleId, string $ssd, string $locale = 'ru_RU'): Command
    {
        return new Command('listQuickGroup', [
            'Accept-Language' => $locale,
            'catalog' => $catalog,
            'vehicleId' => $vehicleId,
            'ssd' => $ssd
        ], 'oem', GroupObject::class, false);
    }

    public static function listQuickDetail(string $catalog, string $vehicleId, string $ssd, string $groupId, string $locale = 'ru_RU'): Command
    {
        return new Command('listQuickDetail', [
            'Accept-Language' => $locale,
            'catalog' => $catalog,
            'vehicleId' => $vehicleId,
            'quickGroupId' => $groupId,
            'ssd' => $ssd,
            'all' => '1'
        ], 'oem', QuickDetailListObject::class, true);
    }

    public static function findCatalogsByOem(string $oem, string $locale = 'ru_RU'): Command
    {
        return new Command('findPartReferences', [
            'Accept-Language' => $locale,
            'oem' => $oem
        ], 'oem', PartReferencesListObject::class, true);
    }

    public static function findVehicleByOem(string $catalog, string $oem, string $locale = 'ru_RU'): Command
    {
        return new Command('findApplicableVehicles', [
            'oem' => $oem,
            'catalog' => $catalog,
            'Accept-Language' => $locale
        ], 'oem', VehicleListObject::class, true);
    }

    public static function findPartInVehicle(string $catalog, string $ssd, string $oem, string $locale = 'ru_RU'): Command
    {
        return new Command('getOEMPartApplicability', [
            'catalog' => $catalog,
            'oem' => $oem,
            'ssd' => $ssd,
            'Accept-Language' => $locale
        ], 'oem', OEMPartApplicabilityObject::class, false);
    }

    public static function findPartInVehicleByName(string $catalog, string $vehicleId, string $ssd, string $partName, string $locale = 'ru_RU'): Command
    {
        return new Command('searchVehicleDetails', [
            'catalog' => $catalog,
            'vehicleId' => $vehicleId,
            'ssd' => $ssd,
            'query' => $partName,
            'Accept-Language' => $locale
        ], 'oem', PartShortListObject::class, true);
    }
}