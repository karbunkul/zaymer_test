<?php

namespace Drupal\zaymer_test;

/**
 * Provides a class User.
 */
class User {

  protected $id;
  protected $name;
  protected $balance;

  const USERS_TABLENAME = 'zaymer_users';

  /**
   * User constructor.
   *
   * @param int $id
   *   User id.
   */
  public function __construct($id) {
    $id = (int) $id;
    if (is_numeric($id)) {
      $this->setId($id);
      $user = \Drupal::database()
        ->select(self::USERS_TABLENAME, 't')
        ->fields('t', [])
        ->condition('t.id', $id)
        ->execute()
        ->fetchAssoc();
      if ($user) {
        $this->setName($user['name'])
          ->setBalance($user['balance']);
      }
    }
  }

  /**
   * Getter for id property.
   *
   * @return int
   *    Property id value.
   */
  public function getId() {
    return (int) $this->id;
  }

  /**
   * Fluent setter for id property.
   *
   * @param int $id
   *    Value for id property.
   *
   * @return $this
   */
  private function setId($id) {
    $this->id = $id;
    return $this;
  }

  /**
   * Getter for name property.
   *
   * @return string
   *    Property name value.
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Fluent setter for name property.
   *
   * @param string $name
   *    Value for name property.
   *
   * @return $this
   */
  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  /**
   * Getter for balance property.
   *
   * @return float
   *    Property balance value.
   */
  public function getBalance() {
    return (float) $this->balance;
  }

  private function setLock($lock = TRUE) {
    $lock = (int) $lock;
    \Drupal::database()
      ->update(self::USERS_TABLENAME)
      ->fields(['locked' => $lock])
      ->condition('id', $this->getId())
      ->execute();
  }

  /**
   * Fluent setter for balance property.
   *
   * @param float $balance
   *    Value for balance property.
   *
   * @return $this
   */
  public function setBalance($balance) {
    $this->balance = $balance;
    return $this;
  }

  /**
   * Check available money for transfer.
   *
   * @param float $value
   *   Money value.
   *
   * @return bool
   *   Check state.
   */
  public function isAvailableMoney($value) {
    $value = (float) $value;
    return ($value <= $this->getBalance()) ? TRUE : FALSE;
  }

  /**
   * Money transfer to other user.
   *
   * @param int $user_id
   *   User id.
   * @param float $value
   *   Money value.
   */
  public function transfer($user_id, $value) {
    $value = (float) $value;
    $user_id = (int) $user_id;

    if (($user = new User($user_id)) && $this->isAvailableMoney($value)) {
      //
      $this->setLock();
      \Drupal::database()
        ->update(self::USERS_TABLENAME)
        ->fields(['balance' => ($this->getBalance() - $value)])
        ->condition('id', $this->getId())
        ->execute();
      $this->setLock(FALSE);
      $this->addComment('Списание - ' . $value);

      //
      $user->setLock();
      \Drupal::database()
        ->update(self::USERS_TABLENAME)
        ->fields(['balance' => ($user->getBalance() + $value)])
        ->condition('id', $user->getId())
        ->execute();
      $user->setLock(FALSE);
      $user->addComment('Зачисление - ' . $value . ' ('.$this->getName().')');
    }
  }

  private function addComment($message) {
    \Drupal::database()
      ->insert('zaymer_comments')
      ->fields(['text' => $message, 'user_id' => $this->getId()])
      ->execute();
  }

}
