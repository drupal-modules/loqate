<?php

namespace Drupal\pca_address\Element;

use Drupal\address\Element\Address;
use Drupal\loqate\PcaAddressElementTrait;

/**
 * Provides an advanced PCA address form element.
 *
 * Usage example:
 * @code
 * $form['address'] = [
 *   '#type' => 'pca_address_advanced',
 *   '#pca_fields' => [
 *     [
 *       'element' => PcaAddressElement::ADDRESS_LOOKUP,
 *     ],
 *     [
 *       'element' => PcaAddressElement::LINE1,
 *       'field' => PcaAddressField::LINE1,
 *       'mode' => PcaAddressMode::POPULATE,
 *     ],
 *     ...
 *   ],
 *   '#pca_options' => [
 *     'key' => config_key_id, // Defaults to key from config.
 *     'countries' => ['codesList' => 'USA,CAN'],
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
 * @FormElement("pca_address_advanced")
 */
class AddressPcaAddress extends Address {

  use PcaAddressElementTrait;

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
    $info['#attached']['library'][] = 'loqate/element.pca_address.address.js';
    return $info;
  }

}
