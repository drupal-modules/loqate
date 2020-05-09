<?php

namespace Drupal\pca_address\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\loqate\PcaAddressFieldMapping\PcaAddressElement;
use Drupal\loqate\PcaAddressFieldMapping\PcaAddressField;
use Drupal\loqate\PcaAddressFieldMapping\PcaAddressMode;

/**
 * Class PcaAddressSettingsForm.
 *
 * @package Drupal\pca_address\Form
 */
class PcaAddressSettingsForm extends ConfigFormBase {

  /**
   * Config key for field mapping.
   */
  public const FIELD_MAPPING = 'field_mapping';

  /**
   * {@inheritdoc)
   */
  public function getFormId() {
    return 'pca_address_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'pca_address.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('pca_address.settings');

    $form[self::FIELD_MAPPING] = [
      '#type' => 'table',
      '#header' => [
        'element' => [
          'data' => $this->t('Element'),
        ],
        'field' => [
          'data' => $this->t('Field'),
        ],
        'mode' => [
          'data' => $this->t('Mode'),
        ],
        'enabled' => [
          'data' => $this->t('Enabled'),
        ],
      ],
      '#empty' => $this->t('No address fields found.'),
    ];

    $rows = [];
    $pca_address_elements = array_flip(PcaAddressElement::getConstants());
    foreach (PcaAddressElement::getConstants() as $field_name) {
      $default_values = [];
      foreach ($config->get(self::FIELD_MAPPING) as $field_map) {
        if ($field_map['element'] === $field_name) {
          $default_values['field'] = $field_map['field'];
          $default_values['mode'] = $field_map['mode'];
          $default_values['enabled'] = TRUE;
          break;
        }
      }

      $rows[$field_name] = [
        'element' => [
          'data' => [
            '#type' => 'markup',
            '#markup' => $field_name,
          ],
        ],
        'field' => [
          'data' => [
            '#type' => 'select',
            '#options' => [
              '' => $this->t('- None -'),
            ] + array_combine(PcaAddressField::getConstants(), PcaAddressField::getConstants()),
            '#default_value' => $default_values['field'] ?? '',
          ],
        ],
        'mode' => [
          'data' => [
            '#type' => 'select',
            '#options' => [
              PcaAddressMode::NONE => $this->t('NONE'),
              PcaAddressMode::SEARCH => $this->t('SEARCH'),
              PcaAddressMode::POPULATE => $this->t('POPULATE'),
              PcaAddressMode::DEFAULT => $this->t('DEFAULT'),
              PcaAddressMode::PRESERVE => $this->t('PRESERVE'),
              PcaAddressMode::COUNTRY => $this->t('COUNTRY'),
            ],
            '#default_value' => $default_values['mode'] ?? PcaAddressMode::DEFAULT,
          ],
        ],
        'enabled' => [
          'data' => [
            '#type' => 'checkbox',
            '#default_value' => $default_values['enabled'] ?? FALSE,
          ],
        ],
      ];
    }

    $form[self::FIELD_MAPPING] += $rows;

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValue(self::FIELD_MAPPING);
    $field_mapping = [];
    foreach ($values as $i => $value) {
      if ((bool) $value['enabled']['data'] === FALSE) {
        continue;
      }
      $field_mapping[] = [
        'element' => $i,
        'field' => $value['field']['data'],
        'mode' => (int) $value['mode']['data'],
      ];
    }
    $this->config('pca_address.settings')
      ->set(self::FIELD_MAPPING, $field_mapping)
      ->save();
    parent::submitForm($form, $form_state);
  }

}
