<?php

namespace Drupal\pca_webform\Element;

use Drupal\webform\Element\WebformCompositeBase;

/**
 * Provides a webform element for an address element.
 *
 * @FormElement("webform_address_loqate")
 */
class WebformAddressLoqate extends WebformCompositeBase {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    return parent::getInfo() + [
      '#theme' => 'webform_composite_address',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function preRenderWebformCompositeFormElement($element) {
    $element = parent::preRenderWebformCompositeFormElement($element);

    // Address lookup wrapper & trigger class.
    $element['#attributes']['class'][] = 'address-lookup';

    foreach (array_keys($element['#webform_composite_elements']) as $key) {
      if ($key !== 'postal_code') {
        // Initial class for hidden fields.
        $element[$key]['#wrapper_attributes']['class'][] = 'address-lookup__field--initial';
      }
      // Generic class and data attribute.
      $element[$key]['#wrapper_attributes']['class'][] = 'address-lookup__field';
      $element[$key]['#wrapper_attributes']['data-key'] = $element['#webform_key'];
    }

    $element['#attached'] = [
      'library' => [
        'pca_webform/element.pca_webform.address.js',
      ],
      'drupalSettings' => [
        'loqate' => [
          'loqate' => [
            'key' => \Drupal::config('loqate.loqateapikeyconfig')->get('loqate_api_key'),
          ],
        ],
      ],
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function getCompositeElements(array $element) {

    $t = \Drupal::translation();

    $elements = [];

    $elements['address'] = [
      '#type' => 'textfield',
      '#title' => $t->translate('Address'),
    ];

    $elements['address_2'] = [
      '#type' => 'textfield',
      '#title' => $t->translate('Address 2'),
    ];

    $elements['city'] = [
      '#type' => 'textfield',
      '#title' => $t->translate('City/Town'),
    ];

    $elements['region'] = [
      '#type' => 'textfield',
      '#title' => $t->translate('Region'),
    ];

    $elements['state_province'] = [
      '#type' => 'select',
      '#title' => $t->translate('State/Province'),
      '#options' => 'state_province_names',
      '#empty_option' => '',
    ];

    $elements['postal_code'] = [
      '#type' => 'textfield',
      '#title' => $t->translate('Zip/Postal Code'),
    ];

    $elements['country'] = [
      '#type' => 'select',
      '#title' => $t->translate('Country'),
      '#options' => 'country_names',
      '#empty_option' => '',
    ];

    return $elements;
  }

}
