<?php

namespace Drupal\pca_address\Element;

use Drupal\address\Element\Address;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a PCA address form element.
 *
 * Usage example:
 * @code
 * $form['address'] = [
 *   '#type' => 'pca_address',
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
    $info['#attached']['library'][] = 'pca_address/element.pca_address.address.js';
    return $info;
  }

  /**
   * Process the PCA address form element.
   */
  public static function processPcaAddress(array &$element, FormStateInterface $form_state, array &$complete_form) {
    $element['address_lookup'] = [
      '#type' => 'textfield',
      '#title' => t('Search Address'),
      '#weight' => -150,
    ];
    $element['#attached']['drupalSettings']['pca_address']['elements'][] = '#' . $element['#id'];
    return $element;
  }

}
