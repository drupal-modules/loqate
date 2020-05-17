<?php

namespace Drupal\loqate\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\FormElement;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\loqate\PcaAddressFieldMapping\PcaAddressElement;
use Drupal\loqate\PcaAddressTrait;

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

  use PcaAddressTrait;

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      '#process' => [
        [$class, 'processAddress'],
        [$class, 'processPcaAddress'],
      ],
      '#pca_fields' => [],
      '#pca_options' => [],
      '#show_address_fields' => FALSE,
      '#allow_manual_input' => TRUE,
      '#attached' => [
        // @TODO: Move this to base module.
        'library' => ['loqate/element.pca_address.address.js'],
      ],
      '#theme_wrappers' => ['container'],
    ];
  }

  /**
   * Process the address fields.
   */
  public static function processAddress(array &$element, FormStateInterface $form_state, array &$complete_form) {

    // Ensure tree structure in output.
    $element['#tree'] = TRUE;

    $element[PcaAddressElement::LINE1] = [
      '#type' => 'textfield',
      '#title' => new TranslatableMarkup('Address Line 1'),
    ];

    $element[PcaAddressElement::LINE2] = [
      '#type' => 'textfield',
      '#title' => new TranslatableMarkup('Address Line 2'),
    ];

    $element[PcaAddressElement::LOCALITY] = [
      '#type' => 'textfield',
      '#title' => new TranslatableMarkup('City/Town'),
    ];

    $element[PcaAddressElement::ADMINISTRATIVE_AREA] = [
      '#type' => 'textfield',
      '#title' => new TranslatableMarkup('State/Province'),
    ];

    $element[PcaAddressElement::POSTAL_CODE] = [
      '#type' => 'textfield',
      '#title' => new TranslatableMarkup('ZIP/Postal Code'),
    ];

    $element[PcaAddressElement::COUNTRY_CODE] = [
      '#type' => 'textfield',
      '#title' => new TranslatableMarkup('Country'),
    ];

    return $element;
  }

}
