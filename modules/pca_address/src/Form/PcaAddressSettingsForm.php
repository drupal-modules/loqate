<?php

namespace Drupal\pca_address\Form;

use Drupal\address\LabelHelper;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
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

    $form[self::FIELD_MAPPING] = [
      '#type' => 'table',
      '#title' => $this->t('Field mapping'),
      '#header' => [
        $this->t('Element'),
        $this->t('Field'),
        $this->t('Mode'),
      ],
    ];

    foreach (LabelHelper::getGenericFieldLabels() as $field_name => $label) {
      $form[self::FIELD_MAPPING][$field_name] = [
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
          '#options' => [
            PcaAddressMode::NONE => $this->t('NONE'),
            PcaAddressMode::SEARCH => $this->t('SEARCH'),
            PcaAddressMode::POPULATE => $this->t('POPULATE'),
            PcaAddressMode::DEFAULT => $this->t('DEFAULT'),
            PcaAddressMode::PRESERVE => $this->t('PRESERVE'),
            PcaAddressMode::COUNTRY => $this->t('COUNTRY'),
          ],
          '#default_value' => PcaAddressMode::DEFAULT,
        ],
      ];
    }

    return parent::buildForm($form, $form_state);
  }

}
