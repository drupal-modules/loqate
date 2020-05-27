<?php

namespace Drupal\pca_webform\Plugin\WebformElement;

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
 *   dependencies = {
 *     "loqate",
 *   }
 * )
 *
 * @see \Drupal\loqate\Element\PcaAddress
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

}
