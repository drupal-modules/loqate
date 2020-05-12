<?php

namespace Drupal\pca_address\Element;

use Drupal\address\Element\Address;
use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Render\Element;
use Drupal\Core\Url;
use Drupal\loqate\PcaAddressFieldMapping\PcaAddressElement;
use Drupal\pca_address\Form\PcaAddressSettingsForm;

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
 *   '#show_address_fields' => FALSE,
 *   '#allow_manual_input' => TRUE,
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
    $info['#show_address_fields'] = FALSE;
    $info['#allow_manual_input'] = TRUE;
    $info['#attached']['library'][] = 'pca_address/element.pca_address.address.js';
    return $info;
  }

  /**
   * Process the PCA address form element.
   */
  public static function processPcaAddress(array &$element, FormStateInterface $form_state, array &$complete_form) {
    // Wrap render array in a wrapper before doing anything.
    self::wrapAddressFieldsInit($element);
    // Add a lookup text field.
    self::addAddressLookupField($element);
    // Add a label field for plain text details.
    self::addAddressLabelField($element);
    // Prepare field mapping specification.
    self::preparePcaFieldMapping($element);
    // Prepare and expose options to Drupal Settings.
    self::preparePcaOptions($element);
    // Add a generic class for all PCA Address elements.
    $element['#attributes']['class'][] = 'pca-address';
    // Expose more attributes to Drupal Settings.
    $element['#attached']['drupalSettings']['pca_address']['elements']['#' . $element['#id']]['show_address_fields'] = $element['#show_address_fields'];
    $element['#attached']['drupalSettings']['pca_address']['elements']['#' . $element['#id']]['allow_manual_input'] = $element['#allow_manual_input'];
    return $element;
  }

  /**
   * Alters the original render array in favor of PCA.
   *
   * @param array $element
   *   Element array.
   */
  private static function wrapAddressFieldsInit(array &$element): void {
    // Wrap original elements in a wrapper w/o affecting the render array
    // structure.
    $wrapper_id = Html::getUniqueId($element['#name'] . '-address-wrapper');
    // Check if we need to hide the address fields initially.
    $wrapper_class = '';
    if ($element['#show_address_fields'] !== TRUE) {
      $wrapper_class = 'hidden';
    }
    $children = Element::children($element);
    foreach ($children as $i => $key) {
      if (isset($element[$key]) && !empty($element[$key])) {
        // Prefix first child.
        if ($i === 0) {
          $element[$key]['#prefix'] = '<div id="' . $wrapper_id . '" class="' . $wrapper_class . '">';
        }
        // Suffix last child.
        elseif ($i === count($children) - 1) {
          $element[$key]['#suffix'] = '</div>';
        }
      }
    }
    // Expose address wrapper id to Drupal Settings.
    $element['#attached']['drupalSettings']['pca_address']['elements']['#' . $element['#id']]['address_wrapper'] = $wrapper_id;
  }

  /**
   * Adds a lookup field.
   *
   * @param array $element
   *   Element array.
   */
  private static function addAddressLookupField(array &$element): void {
    // Add an address lookup field.
    $element[PcaAddressElement::ADDRESS_LOOKUP] = [
      '#type' => 'textfield',
      '#title' => \Drupal::translation()->translate('Search Address'),
      '#weight' => -150,
      '#placeholder' => \Drupal::translation()->translate('Start typing your address'),
    ];
    // Determine if we need to add a manual input link.
    if ($element['#show_address_fields'] !== TRUE && $element['#allow_manual_input'] === TRUE) {
      $manual_input_link = Link::fromTextAndUrl('Click here', Url::fromUserInput('#enter-address'));
      $element[PcaAddressElement::ADDRESS_LOOKUP]['#suffix'] = '<span class="manual-address">' . \Drupal::translation()->translate('@link to enter your address manually.', [
        '@link' => $manual_input_link->toString(),
      ]) . '</span>';
    }
  }

  /**
   * Adds a address label field.
   *
   * @param array $element
   *   Element array.
   */
  private static function addAddressLabelField(array &$element): void {
    // Add an address label field for plain text details.
    $element['address_label'] = [
      '#type' => 'fieldset',
      '#title' => \Drupal::translation()->translate('Address'),
      '#markup' => '<span></span>',
      '#weight' => -140,
      '#attributes' => [
        'class' => ['address-label', 'hidden'],
      ],
    ];
    // Determine if we need to add an edit address link.
    if ($element['#allow_manual_input'] === TRUE) {
      $edit_input_link = Link::fromTextAndUrl('Edit address', Url::fromUserInput('#edit-address'));
      $element['address_label']['#markup'] .= $edit_input_link->toString();
    }
  }

  /**
   * Processes field mapping output.
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
    // Fallback to settings.
    if (!isset($element['#pca_fields']) || empty($element['#pca_fields'])) {
      $element['#pca_fields'] = \Drupal::config('pca_address.settings')->get(PcaAddressSettingsForm::PCA_FIELDS);
    }
    // Start normalising value output.
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
