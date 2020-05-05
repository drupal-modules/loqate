<?php

namespace Drupal\pca_address\Plugin\Field\FieldWidget;

use Drupal\address\Plugin\Field\FieldWidget\AddressDefaultWidget;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'pca_address' widget.
 *
 * @FieldWidget(
 *   id = "pca_address",
 *   label = @Translation("PCA Address"),
 *   field_types = {
 *     "address"
 *   },
 * )
 */
class PcaAddressWidget extends AddressDefaultWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element,$form, $form_state);

    // Add lookup field.
    $element['lookup'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search Address'),
      '#weight' => -150,
    ];

    // Postcode Anywhere Address JS.
    $element['#attached']['library'][] = 'pca_address/libraries.loqate.address.js';

    return $element;
  }

}
