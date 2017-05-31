<?php

namespace Drupal\github\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * A basic form to save github token.
 */
class GithubSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'github_token_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'github.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('github.settings');

    $form['github_token'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Github API Token'),
      '#default_value' => $config->get('token'),
      '#description' => $this->t('Please access to https://github.com/settings/tokens to get more information'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('github.settings')
      ->set('token', $form_state->getValue('github_token'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
