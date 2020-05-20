<?php

namespace Drupal\loqate\PcaAddressFieldMapping;

/**
 * Field mapping element for PCA Address.
 */
final class PcaAddressElement {

  public const LINE1 = 'address_line1';

  public const LINE2 = 'address_line2';

  public const LOCALITY = 'locality';

  public const DEPENDENT_LOCALITY = 'dependent_locality';

  public const ADMINISTRATIVE_AREA = 'administrative_area';

  public const POSTAL_CODE = 'postal_code';

  public const SORTING_CODE = 'sorting_code';

  public const ORGANIZATION = 'organization';

  public const COUNTRY_CODE = 'country_code';

  static function getConstants() {
    return (new \ReflectionClass(__CLASS__))->getConstants();
  }

}
