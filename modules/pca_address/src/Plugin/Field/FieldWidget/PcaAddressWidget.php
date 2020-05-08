<?php

namespace Drupal\pca_address\Plugin\Field\FieldWidget;

use Drupal\address\LabelHelper;
use Drupal\address\Plugin\Field\FieldWidget\AddressDefaultWidget;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\pca_address\FieldMapping\PcaAddressMode;

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
  public static function defaultSettings() {
    return [
      'field_mapping' => [],
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['field_mapping'] = [
      '#type' => 'table',
      '#title' => $this->t('Field mapping'),
      '#header' => [
        $this->t('Element'),
        $this->t('Field'),
        $this->t('Mode'),
      ],
    ];

    foreach (LabelHelper::getGenericFieldLabels() as $field_name => $label) {
      $element['field_mapping'][$field_name] = [
        'element' => [
          '#type' => 'markup',
          '#markup' => $label,
        ],
        'field' => [
          '#type' => 'select',
          '#options' => [
          ],
        ],
        'mode' => [
          '#type' => 'select',
          '#multiple' => TRUE,
          '#options' => [
            PcaAddressMode::NONE => $this->t('NONE'),
            PcaAddressMode::SEARCH => $this->t('SEARCH'),
            PcaAddressMode::POPULATE => $this->t('POPULATE'),
            PcaAddressMode::DEFAULT => $this->t('DEFAULT'),
            PcaAddressMode::PRESERVE => $this->t('PRESERVE'),
            PcaAddressMode::COUNTRY => $this->t('COUNTRY'),
          ],
        ],
      ];
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element,$form, $form_state);
    $widget_settings = $this->getSettings();
    // Override to PCA address variant.
    $element['address']['#type'] = 'pca_address';
    // Set field mapping settings.
    $element['address']['#field_mapping'] = $widget_settings['field_mapping'];
    return $element;
  }

}
