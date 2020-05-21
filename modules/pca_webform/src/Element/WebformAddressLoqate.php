<?php

namespace Drupal\pca_webform\Element;

use Drupal\loqate\Loqate;
use Drupal\webform\Element\WebformCompositeBase;

/**
 * Provides a Webform element for an address element.
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
            'key' => Loqate::getApiKey(),
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

    $drupal_translation = \Drupal::translation();

    $elements = [];

    $elements['address'] = [
      '#type' => 'textfield',
      '#title' => $drupal_translation->translate('Address'),
    ];

    $elements['address_2'] = [
      '#type' => 'textfield',
      '#title' => $drupal_translation->translate('Address 2'),
    ];

    $elements['city'] = [
      '#type' => 'textfield',
      '#title' => $drupal_translation->translate('City/Town'),
    ];

    $elements['region'] = [
      '#type' => 'textfield',
      '#title' => $drupal_translation->translate('Region'),
    ];

    $elements['state_province'] = [
      '#type' => 'select',
      '#title' => $drupal_translation->translate('State/Province'),
      '#options' => 'state_province_names',
      '#empty_option' => '',
    ];

    $elements['postal_code'] = [
      '#type' => 'textfield',
      '#title' => $drupal_translation->translate('Zip/Postal Code'),
    ];

    $elements['country'] = [
      '#type' => 'select',
      '#title' => $drupal_translation->translate('Country'),
      '#options' => 'country_names',
      '#empty_option' => '',
    ];

    return $elements;
  }

}
