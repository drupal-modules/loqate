<?php

namespace Drupal\loqate\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'pca_address_default' formatter.
 *
 * @FieldFormatter(
 *   id = "pca_address_default",
 *   label = @Translation("PCA address"),
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
    $elements = [];
    foreach ($items as $delta => $item) {
      if (!$item->isEmpty()) {
        $elements[$delta] = [
          '#markup' => implode('</br>', array_filter($item->toArray())),
        ];
      }
    }
    return $elements;
  }

}
