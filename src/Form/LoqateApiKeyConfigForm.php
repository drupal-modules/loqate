<?php

namespace Drupal\loqate\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Class LoqateApiKeyConfigForm.
 */
class LoqateApiKeyConfigForm extends ConfigFormBase {

  /**
   * Config key for the default API key.
   */
  public const DEFAULT_API_KEY = 'loqate_api_key';

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

    $read_more_url = Url::fromUri('https://www.loqate.com/resources/support/setup-guides/advanced-setup-guide/#creating_a_key');
    $description_read_more_link = Link::fromTextAndUrl('Read more about Loqate API.', $read_more_url)->toString();

    $form[self::DEFAULT_API_KEY] = [
      '#type' => 'key_select',
      '#title' => $this->t('Default Loqate API key'),
      '#description' => $description_read_more_link,
      '#default_value' => $config->get(self::DEFAULT_API_KEY),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('loqate.loqateapikeyconfig')
      ->set(self::DEFAULT_API_KEY, $form_state->getValue(self::DEFAULT_API_KEY))
      ->save();
  }

}
