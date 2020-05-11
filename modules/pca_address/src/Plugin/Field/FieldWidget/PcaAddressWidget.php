<?php

namespace Drupal\pca_address\Plugin\Field\FieldWidget;

use Drupal\address\Plugin\Field\FieldWidget\AddressDefaultWidget;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

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
      'pca_fields' => [],
      'pca_options' => [],
      'show_address_fields' => FALSE,
      'allow_manual_input' => TRUE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['show_address_fields'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show address fields'),
      '#default_value' => $this->getSetting('show_address_fields'),
    ];

    $form['allow_manual_input'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Allow manual input'),
      '#default_value' => $this->getSetting('allow_manual_input'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $widget_settings = $this->getSettings();

    $settings_link = Link::fromTextAndUrl('PCA Address', Url::fromRoute('pca_address.settings_form'));
    $summary[] = $this->t('See @link for field mapping.', ['@link' => $settings_link->toString()]);

    $summary[] = $this->t('Show address fields: @bool', [
      '@bool' => (bool) $widget_settings['show_address_fields'] ? 'Yes' : 'No',
    ]);

    $summary[] = $this->t('Allow manual input: @bool', [
      '@bool' => (bool) $widget_settings['allow_manual_input'] ? 'Yes' : 'No',
    ]);

    return $summary;
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
    $element['address']['#pca_fields'] = $widget_settings['pca_fields'];
    // Set options settings.
    $element['address']['#pca_options'] = $widget_settings['pca_options'];
    // Set options settings.
    $element['address']['#show_address_fields'] = (bool) $widget_settings['show_address_fields'];
    // Set options settings.
    $element['address']['#allow_manual_input'] = (bool) $widget_settings['allow_manual_input'];
    return $element;
  }

}
