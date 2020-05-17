<?php

namespace Drupal\loqate\Element;

use Drupal\Core\Render\Element\FormElement;

/**
 * Provides a standalone Loqate PCA address form element.
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
 * @FormElement("pca_address")
 */
class LoqatePcaAddress extends FormElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [

    ];
  }

}
