<?php

namespace Drupal\loqate\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'pca_address_default' formatter.
 *
 * @FieldFormatter(
 *   id = "pca_address_default",
 *   label = @Translation("PCA Address"),
 *   field_types = {
 *     "pca_address"
 *   },
 * )
 */
class LoqatePcaAddressDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    // TODO: Implement viewElements() method.
  }

}
