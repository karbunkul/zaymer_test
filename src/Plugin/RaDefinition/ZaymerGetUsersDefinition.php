<?php

namespace Drupal\zaymer_test\Plugin\RaDefinition;

use Drupal\ra\RaDefinitionBase;
use Drupal\zaymer_test\Zaymer;
use Emerap\Ra\Core\Error;
use Emerap\Ra\Core\Parameter;

/**
 * Zaymer API method getUsers.
 *
 * @RaDefinition(
 *   id = "zaymer.getUsers",
 *   description = @Translation("Zaymer API method getUsers")
 * )
 */
class ZaymerGetUsersDefinition extends RaDefinitionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($params) {
    return Zaymer::getUsers();
  }

}
