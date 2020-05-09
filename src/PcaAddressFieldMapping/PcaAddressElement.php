<?php

namespace Drupal\loqate\PcaAddressFieldMapping;

/**
 * Field mapping element for PCA Address.
 */
final class PcaAddressElement {

  public const ADDRESS_LOOKUP = 'address_lookup';

  public const LINE1 = 'address_line1';

  public const LINE2 = 'address_line2';

  public const LOCALITY = 'locality';

  public const POSTAL_CODE = 'postal_code';

  static function getConstants() {
    return (new \ReflectionClass(__CLASS__))->getConstants();
  }

}
