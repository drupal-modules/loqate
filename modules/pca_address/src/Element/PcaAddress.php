<?php

namespace Drupal\pca_address\Element;

use Drupal\address\Element\Address;
use Drupal\Core\Form\FormStateInterface;
use Drupal\pca_address\FieldMapping\PcaAddressElement;

/**
 * Provides a PCA address form element.
 *
 * Usage example:
 * @code
 * $form['address'] = [
 *   '#type' => 'pca_address',
 *   '#field_mapping' => [
 *     [
 *       'element' => PcaAddressElement::ORGANIZATION,
 *       'field' => PcaAddressField::COMPANY,
 *       'mode' => [
 *         PcaAddressMode::DEFAULT,
 *         PcaAddressMode::PRESERVE,
 *       ],
 *     ],
 *     [
 *       'element' => PcaAddressElement::ADDRESS_LINE2,
 *       'field' => PcaAddressField::Line2,
 *       'mode' => PcaAddressMode::POPULATE,
 *     ],
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
    $info['#field_mapping'] = [];
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
    // Process field mapping specification.
    if (isset($element['#field_mapping'])) {
      // Normalise field mapping.
      self::normaliseFieldMapping($element);
      // Expose the field_mapping options to Drupal Settings.
      $element['#attached']['drupalSettings']['pca_address']['elements']['#' . $element['#id']]['fields'] = $element['#field_mapping'];
    }
    // Prepare and expose options to Drupal Settings.
    $element['#attached']['drupalSettings']['pca_address']['options'] = [
      'key' => \Drupal::config('loqate.loqateapikeyconfig')->get('loqate_api_key')
    ];
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
   */
  private static function normalisefieldMapping(array &$element): void {
    foreach ($element['#field_mapping'] as $i => $field_mapping) {
      // Add context for our element selectors.
      if (isset($field_mapping['element']) && !empty($field_mapping['element'])) {
        // Prepend id on field map.
        $element['#field_mapping'][$i]['element'] = "{$element['#name']}[{$field_mapping['element']}]";
      }
      // Fallback value.
      if (!isset($field_mapping['field']) || empty($field_mapping['field'])) {
        $element['#field_mapping'][$i]['field'] = '';
      }
    }
  }

}
