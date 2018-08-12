<?php

namespace Drupal\loqate\Element;

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
    // Getting the configuration key.
    $loqateApikey = \Drupal::configFactory()->getEditable('loqate.loqateapikeyconfig')->get('loqate_api_key');
    return parent::getInfo() + [
      '#theme' => 'webform_composite_address',
      '#attached' => [
        'library' => [
          'loqate/loqate',
          ],
        'drupalSettings' => [
          'loqate' => [
            'loqate' => [
              'key' => $loqateApikey,
            ],
          ],
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function getCompositeElements(array $elements) {
    $elements = [];
    
    $elements['address'] = [
      '#type' => 'textfield',
      '#title' => t('Address'),
      '#prefix' => "<div class='address-lookup address-lookup--initial'>",
      '#suffix' => "</div>",
    ];
    $elements['address_2'] = [
      '#type' => 'textfield',
      '#title' => t('Address 2'),
      '#prefix' => "<div class='address-lookup address-lookup--initial'>",
      '#suffix' => "</div>",
    ];
    $elements['city'] = [
      '#type' => 'textfield',
      '#title' => t('City/Town'),
      '#prefix' => "<div class='address-lookup address-lookup--initial'>",
      '#suffix' => "</div>",
    ];
    $elements['state_province'] = [
      '#type' => 'select',
      '#title' => t('State/Province'),
      '#options' => 'state_province_names',
      '#empty_option' => '',
      '#prefix' => "<div class='address-lookup address-lookup--initial'>",
      '#suffix' => "</div>",
    ];
    $elements['postal_code'] = [
      '#type' => 'textfield',
      '#title' => t('Zip/Postal Code'),
      '#prefix' => "<div class='address-lookup address-lookup--initial'>",
      '#suffix' => "</div>",
    ];
    $elements['country'] = [
      '#type' => 'select',
      '#title' => t('Country'),
      '#options' => 'country_names',
      '#prefix' => "<div class='address-lookup address-lookup--initial'>",
      '#suffix' => "</div>",
      '#empty_option' => '',
    ];
    return $elements;
  }

}
