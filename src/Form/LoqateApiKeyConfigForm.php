<?php

namespace Drupal\loqate\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class LoqateApiKeyConfigForm.
 */
class LoqateApiKeyConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'loqate.loqateapikeyconfig',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'loqate_api_key_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('loqate.loqateapikeyconfig');
    $form['loqate_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Loqate Api key'),
      '#description' => $this->t('Provide the api key from https://account.loqate.com/, Read more from https://www.loqate.com/resources/support/setup-guides/advanced-setup-guide/#creating_a_key'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('loqate_api_key'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('loqate.loqateapikeyconfig')
      ->set('loqate_api_key', $form_state->getValue('loqate_api_key'))
      ->save();
  }

}
