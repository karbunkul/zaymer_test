<?php

namespace Drupal\zaymer_test\Plugin\RaDatatype;

use Drupal\ra\RaDatatypeBase;
use Drupal\zaymer_test\User;
use Emerap\Ra\RaConfig;

/**
 * Zaymer user ID.
 *
 * @RaDatatype(
 *   id = "zaymer_user",
 *   label = @Translation("Zaymer user ID"),
 *   description = @Translation("Zaymer user ID")
 * )
 */
class ZaymerUserType extends RaDatatypeBase {

  /**
   * {@inheritdoc}
   */
  public function check(&$value, $definition) {
    if ($user = new User($value)) {
      $value = $user;
      return TRUE;
    }
    return RaConfig::instanceError(600);
  }

}
