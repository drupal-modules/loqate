<?php

namespace Drupal\loqate\PcaAddressFieldMapping;

/**
 * Field mapping mode for PCA Address.
 */
final class PcaAddressMode {

  public const NONE = 0;

  public const SEARCH = 1;

  public const POPULATE = 2;

  public const DEFAULT = 3;

  public const PRESERVE = 4;

  public const COUNTRY = 8;

}
