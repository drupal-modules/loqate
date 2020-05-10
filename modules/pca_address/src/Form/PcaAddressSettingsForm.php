<?php

namespace Drupal\pca_address\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
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
  public const PCA_FIELDS = 'pca_fields';

  /**
   * Config key for PCA options.
   */
  public const PCA_OPTIONS = 'pca_options';

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

    $form['field_mapping'] = [
      '#title' => $this->t('Field mapping'),
      '#type' => 'details',
      '#open' => TRUE,
    ];

    $doc_link = Link::fromTextAndUrl('Mapping your fields', Url::fromUri('https://www.loqate.com/resources/support/setup-guides/advanced-setup-guide/#mapping_fields'));
    $doc_markup = $this->t('These settings are used as the fields object when instantiating a pca.Address object. See @link for more details about the field mapping format.', [
      '@link' => $doc_link->toString(),
    ]);

    $form['field_mapping']['description'] = [
      '#markup' => '<p>' . $doc_markup . '</p>',
    ];

    $form['field_mapping'][self::PCA_FIELDS] = [
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
    foreach (PcaAddressElement::getConstants() as $field_name) {
      $default_values = [];
      foreach ($config->get(self::PCA_FIELDS) as $field_map) {
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

    $form['field_mapping'][self::PCA_FIELDS] += $rows;

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValue(self::PCA_FIELDS);
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
      ->set(self::PCA_FIELDS, $field_mapping)
      ->save();
    parent::submitForm($form, $form_state);
  }

}
