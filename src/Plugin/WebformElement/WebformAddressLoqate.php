<?php

namespace Drupal\loqate\Plugin\WebformElement;

use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform\Plugin\WebformElement\WebformCompositeBase;

/**
 * Provides an 'address' element.
 *
 * @WebformElement(
 *   id = "webform_address_loqate",
 *   label = @Translation("Address Loqate"),
 *   description = @Translation("Loqate API provides a form element to collect
 * address information (street, city, state, zip)."),
 *   category = @Translation("Composite elements"),
 *   multiline = TRUE,
 *   composite = TRUE,
 *   states_wrapper = TRUE,
 * )
 */
class WebformAddressLoqate extends WebformCompositeBase {

  /**
   * {@inheritdoc}
   */
  protected function formatHtmlItemValue(array $element, WebformSubmissionInterface $webform_submission, array $options = []) {
    return $this->formatTextItemValue($element, $webform_submission, $options);
  }

  /**
   * {@inheritdoc}
   */
  protected function formatTextItemValue(array $element, WebformSubmissionInterface $webform_submission, array $options = []) {
    $value = $this->getValue($element, $webform_submission, $options);

    $location = '';
    if (!empty($value['city'])) {
      $location .= $value['city'];
    }
    if (!empty($value['state_province'])) {
      $location .= ($location) ? ', ' : '';
      $location .= $value['state_province'];
    }
    if (!empty($value['postal_code'])) {
      $location .= ($location) ? '. ' : '';
      $location .= $value['postal_code'];
    }

    $lines = [];
    if (!empty($value['address'])) {
      $lines['address'] = $value['address'];
    }
    if (!empty($value['address_2'])) {
      $lines['address_2'] = $value['address_2'];
    }
    if ($location) {
      $lines['location'] = $location;
    }
    if (!empty($value['country'])) {
      $lines['country'] = $value['country'];
    }
    return $lines;
  }

}
