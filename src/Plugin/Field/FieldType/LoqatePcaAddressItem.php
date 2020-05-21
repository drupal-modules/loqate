<?php

namespace Drupal\loqate\Plugin\Field\FieldType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\loqate\PcaAddressFieldMapping\PcaAddressElement;

/**
 * PCA address field item.
 *
 * @FieldType(
 *   id = "pca_address",
 *   label = @Translation("PCA address"),
 *   description = @Translation("An entity field containing a postal address"),
 *   category = @Translation("Address"),
 *   default_widget = "pca_address_default",
 *   default_formatter = "pca_address_default",
 *   list_class = "\Drupal\Core\Field\FieldItemList",
 * )
 */
class LoqatePcaAddressItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = [];

    $properties[PcaAddressElement::LINE1] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Address Line 1'));

    $properties[PcaAddressElement::LINE2] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Address Line 2'));

    $properties[PcaAddressElement::LOCALITY] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('City/Town'));

    $properties[PcaAddressElement::ADMINISTRATIVE_AREA] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('State/Province'));

    $properties[PcaAddressElement::POSTAL_CODE] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('ZIP/Postal Code'));

    $properties[PcaAddressElement::COUNTRY_CODE] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Country'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $schema = [];

    $schema['columns'][PcaAddressElement::LINE1] = [
      'type' => 'varchar',
      'length' => 255,
      'default' => NULL,
    ];

    $schema['columns'][PcaAddressElement::LINE2] = [
      'type' => 'varchar',
      'length' => 255,
      'default' => NULL,
    ];

    $schema['columns'][PcaAddressElement::LOCALITY] = [
      'type' => 'varchar',
      'length' => 255,
      'default' => NULL,
    ];

    $schema['columns'][PcaAddressElement::ADMINISTRATIVE_AREA] = [
      'type' => 'varchar',
      'length' => 255,
      'default' => NULL,
    ];

    $schema['columns'][PcaAddressElement::POSTAL_CODE] = [
      'type' => 'varchar',
      'length' => 255,
      'default' => NULL,
    ];

    $schema['columns'][PcaAddressElement::COUNTRY_CODE] = [
      'type' => 'varchar',
      'length' => 255,
      'default' => NULL,
    ];

    return $schema;
  }

}
