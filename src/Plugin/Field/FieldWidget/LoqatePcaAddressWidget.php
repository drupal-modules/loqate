<?php

namespace Drupal\loqate\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'pca_address' widget.
 *
 * @FieldWidget(
 *   id = "pca_address",
 *   label = @Translation("PCA Address"),
 *   field_types = {
 *     "pca_address"
 *   },
 * )
 */
class LoqatePcaAddressWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $field_settings = $this->getFieldSettings();
    $widget_settings = $this->getSettings();

    $element['address'] = [
      '#type' => 'pca_address',
      '#default_value' => $items[$delta]->value ?? NULL,
      '#required' => $this->fieldDefinition->isRequired(),
    ];

    return $element;
  }

}
