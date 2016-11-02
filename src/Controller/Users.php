<?php

namespace Drupal\zaymer_test\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\zaymer_test\Zaymer;
use PDO;

/**
 * Provides a class Users.
 */
class Users extends ControllerBase {

  public function main() {
    $rows = [];

    $query = \Drupal::database()->select('zaymer_users', 'u');
    $query->leftJoin('zaymer_comments', 'c', 'u.id = c.user_id');
    $query->fields('u', ['id', 'name', 'balance']);
    $query->fields('c', ['text']);

    $query->orderBy('u.name');
    $query->orderBy('c.user_id', 'DESC');
    $data = $query->execute()->fetchAllAssoc('id', PDO::FETCH_ASSOC);

    foreach ($data as $user) {
      $rows[] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'balance' => $user['balance'],
        'comment' => $user['text'],
      ];
    }
    return [
      '#theme' => 'table',
      '#header' => [
        '#',
        $this->t('Name'),
        $this->t('Balance'),
        $this->t('Last comment'),
      ],
      '#rows' => $rows,
      '#empty' => $this->t('Users not found!'),
    ];
  }

}
