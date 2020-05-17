<?php

namespace Drupal\loqate;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Render\Element;
use Drupal\Core\Url;
use Drupal\pca_address\Form\PcaAddressSettingsForm;

/**
 * Class PcaAddressTrait.
 *
 * @package Drupal\loqate
 */
trait PcaAddressTrait {

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
    // Add an address lookup wrapper.
    $element['address_lookup_wrapper'] = [
      '#type' => 'container',
      '#weight' => -90,
      '#states' => [
        'visible' => [
          'select[name="' . "{$element['#name']}[country_code]" . '"]' => ['filled' => TRUE],
        ],
      ],
    ];
    // Add an address lookup field.
    $element['address_lookup_wrapper']['address_lookup'] = [
      '#type' => 'textfield',
      '#title' => \Drupal::translation()->translate('Search Address'),
      '#placeholder' => \Drupal::translation()->translate('Start typing your address'),
    ];
    // Determine if we need to add a manual input link.
    if ($element['#show_address_fields'] !== TRUE && $element['#allow_manual_input'] === TRUE) {
      $manual_input_link = Link::fromTextAndUrl('Click here', Url::fromUserInput('#manual-address'));
      $element['address_lookup_wrapper']['manual_input_link'] = [
        '#type' => '#markup',
        '#markup' => '<span class="manual-address">' . \Drupal::translation()->translate('@link to enter your address manually.', [
            '@link' => $manual_input_link->toString(),
          ]) . '</span>',
      ];
    }
  }

  /**
   * Adds a address label field.
   *
   * @param array $element
   *   Element array.
   */
  private static function addAddressLabelField(array &$element): void {
    // Do not process a field label if we show address fields initially as that
    // is redundant to display to the end user.
    if ($element['#show_address_fields'] === TRUE) {
      return;
    }
    // Add an address label field for plain text details.
    $element['address_label'] = [
      '#type' => 'fieldset',
      '#title' => \Drupal::translation()->translate('Address'),
      '#markup' => '<span class="address-label"></span>',
      '#weight' => -80,
      '#attributes' => [
        'class' => ['address-label-wrapper', 'hidden'],
      ],
    ];
    // Determine if we need to add an edit address link.
    if ($element['#allow_manual_input'] === TRUE) {
      $edit_input_link = Link::fromTextAndUrl('Edit address', Url::fromUserInput('#edit-address'));
      $element['address_label']['#markup'] .= '<span class="edit-address">' . $edit_input_link->toString() . '</span>';
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
    if (empty($element['#pca_fields'])) {
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
    // Add fields mapping for the nested lookup field element.
    $element['#pca_fields'][] = [
      'element' => "{$element['#name']}[address_lookup_wrapper][address_lookup]",
      'field' => '',
    ];
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
      'key' => Loqate::getApiKey(),
    ];
    // Merge options if provided.
    if (isset($element['#pca_options'])) {
      // Before we merge the options, check on a possibly provided API key value.
      if (isset($element['#pca_options']['key']) && $key = Loqate::getApiKey($element['#pca_options']['key'])) {
        // Replace the config key id with the key value.
        $element['#pca_options']['key'] = $key;
      }
      // If not then unset the key value before merging options.
      else {
        unset($element['#pca_options']['key']);
      }
      $element['#attached']['drupalSettings']['pca_address']['elements']['#' . $element['#id']]['options'] = array_merge(
        $element['#attached']['drupalSettings']['pca_address']['elements']['#' . $element['#id']]['options'], $element['#pca_options']
      );
    }
  }

}
