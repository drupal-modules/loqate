<?php

namespace Drupal\loqate;

use Drupal\loqate\Form\LoqateApiKeyConfigForm;

/**
 * Class Loqate.
 *
 * @package Drupal\loqate
 */
final class Loqate {

  /**
   * Gets the Loqate API key value.
   *
   * @param null $key_id
   *   Optional custom key module key id.
   *
   * @return string|null
   *   The key value if found or NULL.
   */
  public static function getApiKey($key_id = NULL) {
    if ($key_id === NULL) {
      $key_id = \Drupal::config('loqate.loqateapikeyconfig')->get(LoqateApiKeyConfigForm::DEFAULT_API_KEY);
    }
    if ($key_id) {
      /** @var \Drupal\key\KeyRepositoryInterface $key_repository */
      $key_repository = \Drupal::service('key.repository');
      $key_entity = $key_repository->getKey($key_id);
      if ($key_entity) {
        return $key_entity->getKeyValue();
      }
    }
    return NULL;
  }

}
