<?php

namespace Drupal\zaymer_test;

use PDO;

/**
 * Provides a class Zaymer.
 */
class Zaymer {

  /**
   * @return mixed
   */
  public static function getUsers() {

    return \Drupal::database()
      ->select('zaymer_users', 't')
      ->fields('t', [])
      ->execute()
      ->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * @param int $id
   *   User id.
   *
   * @return \Drupal\zaymer_test\User
   *   User object.
   */
  public static function getUser($id) {
    return new User($id);
  }

}
