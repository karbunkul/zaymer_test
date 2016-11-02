<?php

namespace Drupal\zaymer_test\Plugin\RaDefinition;

use Drupal\ra\RaDefinitionBase;
use Drupal\zaymer_test\User;
use Drupal\zaymer_test\Zaymer;
use Emerap\Ra\Core\Error;
use Emerap\Ra\Core\Parameter;
use Emerap\Ra\RaConfig;

/**
 * Zaymer API transfer money.
 *
 * @RaDefinition(
 *   id = "zaymer.transfer",
 *   description = @Translation("Zaymer API transfer money")
 * )
 */
class ZaymerTransferDefinition extends RaDefinitionBase {
  public function getMethodParams() {
    return [
      RaConfig::instanceParam('from', NULL, 'zaymer_user'),
      RaConfig::instanceParam('to', NULL, 'zaymer_user'),
      RaConfig::instanceParam('value', NULL, 'number'),
    ];
  }


  /**
   * {@inheritdoc}
   */
  public function execute($params) {
    /** @var $from User */
    $from = $params['from'];
    /** @var $to User */
    $to = $params['to'];
    $value = $params['value'];
    if ($from->isAvailableMoney($value)) {
      $from->transfer($to->getId(), $params['value']);
      return TRUE;
    }else {
      return RaConfig::instanceError(610);
    }

  }

}
