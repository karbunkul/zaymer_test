<?php

namespace Drupal\zaymer_test\Form;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\zaymer_test\User;
use Drupal\zaymer_test\Zaymer;

/**
 * Class RaPairForm.
 *
 * @package Drupal\ra\Form
 */
class ZaymerForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'zaymer_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#prefix'] = '<div id="zaymer-form-wrapper">';
    $form['#suffix'] = '</div>';

    $users = [];
    foreach (Zaymer::getUsers() as $user) {
      $users[$user['id']] = $user['name'];
    }

    $form['from'] = [
      '#type' => 'select',
      '#title' => $this->t('From'),
      '#options' => $users,
      '#required' => TRUE,
    ];

    $form['to'] = [
      '#type' => 'select',
      '#title' => $this->t('To'),
      '#options' => $users,
      '#required' => TRUE,
    ];

    $form['value'] = [
      '#type' => 'textfield',
      '#attributes' => array(
        'data-type' => 'number',
      ),
      '#title' => $this->t('Value'),
      '#required' => TRUE,
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild();
    $values = $form_state->getValues();
    $value = (float) $form_state->getValue('value');
    $from = new User($form_state->getValue('from'));
    $to = new User($values['to']);
    if ($values['from'] != $values['to']) {
      if ($from->isAvailableMoney($value)) {
        $from->transfer($to->getId(), $value);
        $message = new FormattableMarkup('@value сняты с вашего баланса и переведены @to!', ['@value' => number_format($value, 2), '@to' => $to->getName()]);
        drupal_set_message($message->jsonSerialize());
      }else {

        $message = new FormattableMarkup('@from, на вашем балансе нет нужной суммы!', ['@from' => $from->getName()]);
        drupal_set_message($message->jsonSerialize(), 'warning');
      }
    }
    else {
      drupal_set_message('Вы не можете переводить деньги самому себе!', 'warning');
    }
  }

}
