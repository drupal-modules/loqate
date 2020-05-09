<?php

namespace Drupal\pca_address\Element;

use Drupal\address\Element\Address;
use Drupal\Core\Form\FormStateInterface;
use Drupal\loqate\PcaAddressFieldMapping\PcaAddressElement;

/**
 * Provides a PCA address form element.
 *
 * Usage example:
 * @code
 * $form['address'] = [
 *   '#type' => 'pca_address',
 *   '#pca_fields' => [
 *     [
 *       'element' => PcaAddressElement::ADDRESS_LOOKUP,
 *     ],
 *     [
 *       'element' => PcaAddressElement::ADDRESS_LINE1,
 *       'field' => PcaAddressField::Line1,
 *       'mode' => PcaAddressMode::POPULATE,
 *     ],
 *     ...
 *   ],
 *   '#pca_options' => [
 *     'key' => XXXX-XXXX-XXXX-XXXX, // Defaults to key from config.
 *     'countries' => ['codeList' => 'USA,CAN'],
 *     'setCountryByIP' => false,
 *     ...
 *   ],
 *   ...
 * ];
 * @endcode
 *
 * @see \Drupal\address\Element\Address
 *
 * @FormElement("pca_address")
 */
class PcaAddress extends Address {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $info = parent::getInfo();
    $info['#process'][] = [get_class($this), 'processPcaAddress'];
    $info['#pca_fields'] = [];
    $info['#pca_options'] = [];
    $info['#attached']['library'][] = 'pca_address/element.pca_address.address.js';
    return $info;
  }

  /**
   * Process the PCA address form element.
   */
  public static function processPcaAddress(array &$element, FormStateInterface $form_state, array &$complete_form) {
    // Add a lookup text field.
    $element[PcaAddressElement::ADDRESS_LOOKUP] = [
      '#type' => 'textfield',
      '#title' => t('Search Address'),
      '#weight' => -150,
    ];
    // Prepare field mapping specification.
    self::preparePcaFieldMapping($element);
    // Prepare and expose options to Drupal Settings.
    self::preparePcaOptions($element);
    // Add a generic class for all PCA Address elements.
    $element['#attributes']['class'][] = 'pca-address';
    return $element;
  }

  /**
   * Normalise field mapping output.
   *
   * This method is responsible for:
   * - Validates field mapping as existing fields
   * - Sets context for the element attribute selector
   * - Provides an empty default value for the field attribute
   * - ...
   *
   * @param array $element
   *   Element array.
   *
   * @see https://www.loqate.com/resources/support/setup-guides/advanced-setup-guide/#mapping_fields
   */
  private static function preparePcaFieldMapping(array &$element): void {
    if (isset($element['#pca_fields'])) {
      foreach ($element['#pca_fields'] as $i => $field_mapping) {
        // Add context for our element selectors.
        if (isset($field_mapping['element']) && !empty($field_mapping['element'])) {
          // Prepend id on field map.
          $element['#pca_fields'][$i]['element'] = "{$element['#name']}[{$field_mapping['element']}]";
        }
        // Fallback value.
        if (!isset($field_mapping['field']) || empty($field_mapping['field'])) {
          $element['#pca_fields'][$i]['field'] = '';
        }
      }
      // Expose the field_mapping options to Drupal Settings.
      $element['#attached']['drupalSettings']['pca_address']['elements']['#' . $element['#id']]['fields'] = $element['#pca_fields'];
    }
  }

  /**
   * Processes additional options and overrides.
   *
   * @param array $element
   *   Element array.
   *
   * @see https://www.loqate.com/resources/support/setup-guides/advanced-setup-guide/#setting_options
   */
  private static function preparePcaOptions(array &$element): void {
    $element['#attached']['drupalSettings']['pca_address']['elements']['#' . $element['#id']]['options'] = [
      'key' => \Drupal::config('loqate.loqateapikeyconfig')->get('loqate_api_key'),
    ];
    // Merge options if provided.
    if (isset($element['#pca_options'])) {
      $element['#attached']['drupalSettings']['pca_address']['elements']['#' . $element['#id']]['options'] = array_merge(
        $element['#attached']['drupalSettings']['pca_address']['elements']['#' . $element['#id']]['options'], $element['#pca_options']
      );
    }
  }

}
