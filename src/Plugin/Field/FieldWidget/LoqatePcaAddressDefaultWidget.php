<?php

namespace Drupal\loqate\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\loqate\PcaAddressFieldWidgetTrait;

/**
 * Plugin implementation of the 'pca_address_default' widget.
 *
 * @FieldWidget(
 *   id = "pca_address_default",
 *   label = @Translation("PCA Address"),
 *   field_types = {
 *     "pca_address"
 *   },
 * )
 */
class LoqatePcaAddressDefaultWidget extends WidgetBase {

  use PcaAddressFieldWidgetTrait;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return self::getWidgetDefaultSettings() + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return $this->buildWidgetsettingsForm(parent::settingsForm($form, $form_state));
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    return $this->buildWidgetSettingsSummary();
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $field_settings = $this->getFieldSettings();
    $widget_settings = $this->getSettings();

    $element += [
      '#type' => 'details',
      '#open' => TRUE,
    ];

    $element['address'] = [
      '#type' => 'pca_address',
      '#default_value' => $items[$delta]->value ?? NULL,
      '#required' => $this->fieldDefinition->isRequired(),
    ];

    return $this->buildWidgetFormElement($element);
  }

}
