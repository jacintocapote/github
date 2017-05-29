<?php

namespace Drupal\github\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'github_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "github_formatter",
 *   label = @Translation("Github Username"),
 *   field_types = {
 *     "github"
 *   }
 * )
 */
class GithubDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();
    $settings = $this->getSettings();

    $summary[] = t('Displays the random string.');

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    github_load_library();
    $element = array();   
 
    try {
      $client = new \GithubClient();

      foreach ($items as $delta => $item) {
        $username = $item->value;
        $repos = $client->repos->listUserRepositories($username, 'owner');
        $stargazer = 0;

        if (!empty($repos)) {
          foreach ($repos as $id_repo => $repo) {
            //We need authentication
            $stargazer += $client->activity->starring->listStargazers($username, $id_repo);
          }
        }
       
        // Render each element as markup.
        $element[$delta] = array(
          '#type' => 'markup',
          '#markup' => $item->value,
        );
      }

    } catch(\Exception $e) { }

    return $element;
  }

}
