<?php

namespace Drupal\loqate\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\FormElement;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\loqate\PcaAddressFieldMapping\PcaAddressElement;
use Drupal\loqate\PcaAddressElementTrait;

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

  use PcaAddressElementTrait;

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return $this->buildElementGetInfo() + [
      '#process' => [
        [$class, 'processAddress'],
        [$class, 'processPcaAddress'],
      ],
      '#element_validate' => [
        [$class, 'validateAddress'],
      ],
      '#attached' => [
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
      '#default_value' => $element['#default_value'][PcaAddressElement::LINE1] ?? NULL,
      '#required' => TRUE,
    ];

    $element[PcaAddressElement::LINE2] = [
      '#type' => 'textfield',
      '#title' => new TranslatableMarkup('Address Line 2'),
      '#default_value' => $element['#default_value'][PcaAddressElement::LINE2] ?? NULL,
    ];

    $element[PcaAddressElement::POSTAL_CODE] = [
      '#type' => 'textfield',
      '#title' => new TranslatableMarkup('ZIP/Postal Code'),
      '#default_value' => $element['#default_value'][PcaAddressElement::POSTAL_CODE] ?? NULL,
      '#required' => TRUE,
      '#size' => 10,
    ];

    $element[PcaAddressElement::LOCALITY] = [
      '#type' => 'textfield',
      '#title' => new TranslatableMarkup('City/Town'),
      '#default_value' => $element['#default_value'][PcaAddressElement::LOCALITY] ?? NULL,
      '#required' => TRUE,
      '#size' => 30,
    ];

    $element[PcaAddressElement::ADMINISTRATIVE_AREA] = [
      '#type' => 'textfield',
      '#title' => new TranslatableMarkup('State/Province'),
      '#default_value' => $element['#default_value'][PcaAddressElement::ADMINISTRATIVE_AREA] ?? NULL,
      '#size' => 30,
    ];

    $element[PcaAddressElement::COUNTRY_CODE] = [
      '#type' => 'textfield',
      '#title' => new TranslatableMarkup('Country'),
      '#default_value' => $element['#default_value'][PcaAddressElement::COUNTRY_CODE] ?? NULL,
    ];

    return $element;
  }

  /**
   * Validates the address.
   */
  public static function validateAddress(&$element, FormStateInterface $form_state, &$complete_form) {
    // @TODO implement...
  }

}
