<?php

namespace Drupal\loqate\Plugin\WebformElement;

use Drupal\Core\Form\FormStateInterface;
use Drupal\loqate\PcaAddressFieldMapping\PcaAddressElement;
use Drupal\webform\Plugin\WebformElement\WebformCompositeBase;

/**
 * Provides a 'PCA address' element.
 *
 * @WebformElement(
 *   id = "pca_address",
 *   label = @Translation("PCA address"),
 *   description = @Translation("Provides a form element to collect address information (street, city, state, zip)."),
 *   category = @Translation("Composite elements"),
 *   multiline = TRUE,
 *   composite = TRUE,
 *   states_wrapper = TRUE,
 * )
 *
 * @see \Drupal\loqate\Element\LoqatePcaAddress
 */
class LoqatePcaAddress extends WebformCompositeBase {

  /**
   * {@inheritdoc}
   */
  public function getPluginLabel() {
    return \Drupal::moduleHandler()->moduleExists('pca_address') ? $this->t('Basic PCA address') : parent::getPluginLabel();
  }

  /**
   * {@inheritdoc}
   */
  public function getCompositeElements() {
    return [];
  }

  /**
   * {@inheritdoc}
   *
   * @see \Drupal\loqate\Plugin\Field\FieldType\LoqatePcaAddressItem::schema
   */
  public function initializeCompositeElements(array &$element) {
    $element['#webform_composite_elements'] = [
      PcaAddressElement::LINE1 => [
        '#title' => $this->t('Address Line 1'),
        '#type' => 'textfield',
        '#maxlength' => 255,
      ],
      PcaAddressElement::LINE2 => [
        '#title' => $this->t('Address Line 2'),
        '#type' => 'textfield',
        '#maxlength' => 255,
      ],
      PcaAddressElement::LOCALITY => [
        '#title' => $this->t('City/Town'),
        '#type' => 'textfield',
        '#maxlength' => 255,
      ],
      PcaAddressElement::ADMINISTRATIVE_AREA => [
        '#title' => $this->t('State/Province'),
        '#type' => 'textfield',
        '#maxlength' => 255,
      ],
      PcaAddressElement::POSTAL_CODE => [
        '#title' => $this->t('ZIP/Postal Code'),
        '#type' => 'textfield',
        '#maxlength' => 255,
      ],
      PcaAddressElement::COUNTRY_CODE => [
        '#title' => $this->t('Country'),
        '#type' => 'textfield',
        '#maxlength' => 2,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function defineDefaultProperties() {
    return [
      'pca_fields' => [],
      'pca_options' => [],
      'show_address_fields' => FALSE,
      'allow_manual_input' => TRUE,
      'loqate_api_key' => NULL,
    ] + parent::defineDefaultProperties();
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['pca_address'] = [
      '#type' => 'details',
      '#title' => $this->t('PCA address'),
      '#open' => TRUE,
    ];

    $form['pca_address']['show_address_fields'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show address fields'),
    ];

    $form['pca_address']['allow_manual_input'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Allow manual input'),
    ];

    $form['pca_address']['loqate_api_key'] = [
      '#type' => 'key_select',
      '#title' => $this->t('Loqate API key'),
    ];

    return $form;
  }

}
