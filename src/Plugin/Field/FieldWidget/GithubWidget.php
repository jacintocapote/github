<?php

namespace Drupal\github\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'github' widget.
 *
 * @FieldWidget(
 *   id = "github_widget",
 *   label = @Translation("Github username"),
 *   field_types = {
 *     "github"
 *   }
 * )
 */
class GithubWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    $element += array(
      '#type' => 'textfield',
      '#default_value' => $value,
      '#size' => 60,
      '#maxlength' => 255,
      '#element_validate' => array(
        array($this, 'validate'),
      ),
    );
    return array('value' => $element);
  }

  /**
   * Validate the github username field.
   */
  public function validate($element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if (strlen($value) == 0) {
      $form_state->setValueForElement($element, '');
      return;
    }

    try {
      //Is a valid github user?
      $client = \Drupal::service('github.githubgetclient')->GithubGetClient();
      $githubuser = $client->users->getSingleUser($value);
    } catch(\Exception $e) {
      $form_state->setError($element, t("The github username is invalid. Please insert a valid username."));
    }
  }

}
