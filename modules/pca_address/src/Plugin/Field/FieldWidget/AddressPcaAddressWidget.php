<?php

namespace Drupal\pca_address\Plugin\Field\FieldWidget;

use Drupal\address\Plugin\Field\FieldWidget\AddressDefaultWidget;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\loqate\PcaAddressFieldWidgetTrait;

/**
 * Plugin implementation of the 'pca_address_advanced' widget.
 *
 * @FieldWidget(
 *   id = "pca_address_advanced",
 *   label = @Translation("PCA Address"),
 *   field_types = {
 *     "address"
 *   },
 * )
 */
class AddressPcaAddressWidget extends AddressDefaultWidget {

  use PcaAddressFieldWidgetTrait;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return self::getFieldWidgetDefaultSettings() + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return $this->buildFieldWidgetsettingsForm(parent::settingsForm($form, $form_state));
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    return $this->buildFieldWidgetSettingsSummary();
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element,$form, $form_state);
    // Override to PCA address variant.
    $element['address']['#type'] = 'pca_address_advanced';
    return $this->buildFieldWidgetFormElement($element);
  }

}
