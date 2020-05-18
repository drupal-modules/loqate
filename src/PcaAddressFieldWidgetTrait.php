<?php

namespace Drupal\loqate;

use Drupal\Core\Form\FormStateInterface;

/**
 * Class PcaAddressFieldWidgetTrait.
 *
 * @package Drupal\loqate
 */
trait PcaAddressFieldWidgetTrait {

  /**
   * Get the default settings for the field widget.
   */
  public static function getWidgetDefaultSettings() {
    return [
      'pca_fields' => [],
      'pca_options' => [],
      'show_address_fields' => FALSE,
      'allow_manual_input' => TRUE,
      'loqate_api_key' => NULL,
    ];
  }

  /**
   * Get widget settings form.
   */
  public function buildWidgetsettingsForm(array $form) {

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

    $form['loqate_api_key'] = [
      '#type' => 'key_select',
      '#title' => $this->t('Loqate API key'),
      '#default_value' => $this->getSetting('loqate_api_key'),
    ];

    return $form;
  }

  /**
   * Build the widget settings summary.
   */
  public function buildWidgetSettingsSummary() {
    $summary = [];
    $widget_settings = $this->getSettings();

    $summary[] = $this->t('Show address fields: @bool', [
      '@bool' => (bool) $widget_settings['show_address_fields'] ? 'Yes' : 'No',
    ]);

    $summary[] = $this->t('Allow manual input: @bool', [
      '@bool' => (bool) $widget_settings['allow_manual_input'] ? 'Yes' : 'No',
    ]);

    $summary[] = $this->t('Key ID: @key', [
      '@key' => !empty($widget_settings['loqate_api_key']) ? $widget_settings['loqate_api_key'] : 'None',
    ]);

    return $summary;
  }

  public function buildWidgetFormElement(array $element) {
    $widget_settings = $this->getSettings();
    // Set field mapping settings.
    $element['address']['#pca_fields'] = $widget_settings['pca_fields'];
    // Set options settings.
    $element['address']['#pca_options'] = $widget_settings['pca_options'];
    // Set show address fields bool.
    $element['address']['#show_address_fields'] = (bool) $widget_settings['show_address_fields'];
    // Set allow manual input bool.
    $element['address']['#allow_manual_input'] = (bool) $widget_settings['allow_manual_input'];
    // Set options key if set.
    if ($widget_settings['loqate_api_key']) {
      $element['address']['#pca_options']['key'] = $widget_settings['loqate_api_key'];
    }
    return $element;
  }

}
