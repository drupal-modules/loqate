<?php

namespace Drupal\loqate\PcaAddressFieldMapping;

/**
 * Field mapping field for PCA Address.
 */
final class PcaAddressField {

  public const ID = 'Id';

  public const DOMESTIC_ID = 'DomesticId';

  public const LANGUAGE = 'Language';

  public const LANGUAGE_ALTERNATIVES = 'LanguageAlternatives';

  public const DEPARTMENT = 'Department';

  public const COMPANY = 'Company';

  public const SUB_BUILDING = 'SubBuilding';

  public const BUILDING_NUMBER = 'BuildingNumber';

  public const BUILDING_NAME = 'BuildingName';

  public const SECONDARY_STREET = 'SecondaryStreet';

  public const STREET = 'Street';

  public const BLOCK = 'Block';

  public const NEIGHBOURHOOD = 'Neighbourhood';

  public const DISTRICT = 'District';

  public const CITY = 'City';

  public const LINE1 = 'Line1';

  public const LINE2 = 'Line2';

  public const LINE3 = 'Line3';

  public const LINE4 = 'Line4';

  public const LINE5 = 'Line5';

  public const ADMIN_AREA_NAME = 'AdminAreaName';

  public const ADMIN_AREA_CODE = 'AdminAreaCode';

  public const PROVINCE = 'Province';

  public const PROVINCE_NAME = 'ProvinceName';

  public const PROVINCE_CODE = 'ProvinceCode';

  public const POSTAL_CODE = 'PostalCode';

  public const COUNTRY_NAME = 'CountryName';

  public const COUNTRY_ISO2 = 'CountryIso2';

  public const COUNTRY_ISO3 = 'CountryIso3';

  public const COUNTRY_ISO_NUMBER = 'CountryIsoNumber';

  public const SORTING_NUMBER1 = 'SortingNumber1';

  public const SORTING_NUMBER2 = 'SortingNumber2';

  public const BARCODE = 'Barcode';

  public const PO_BOX_NUMBER = 'POBoxNumber';

  public const LABEL = 'Label';

  public const TYPE = 'Type';

  public const DATA_LEVEL = 'DataLevel';

  public const FIELD1 = 'Field1';

  public const FIELD2 = 'Field2';

  public const FIELD3 = 'Field3';

  public const FIELD4 = 'Field4';

  public const FIELD5 = 'Field5';

  public const FIELD6 = 'Field6';

  public const FIELD7 = 'Field7';

  public const FIELD8 = 'Field8';

  public const FIELD9 = 'Field9';

  public const FIELD10 = 'Field10';

  public const FIELD11 = 'Field11';

  public const FIELD12 = 'Field12';

  public const FIELD13 = 'Field13';

  public const FIELD14 = 'Field14';

  public const FIELD15 = 'Field15';

  public const FIELD16 = 'Field16';

  public const FIELD17 = 'Field17';

  public const FIELD18 = 'Field18';

  public const FIELD19 = 'Field19';

  public const FIELD20 = 'Field20';

  static function getConstants() {
    return (new \ReflectionClass(__CLASS__))->getConstants();
  }

}
