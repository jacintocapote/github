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
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = array();   
 
    try {
      $client = \Drupal::service('github.githubgetclient')->GithubGetClient();

      foreach ($items as $delta => $item) {
        $username = $item->value;
        $githubuser = $client->users->getSingleUser($username);
        $repos = $client->repos->listUserRepositories($username, 'owner');
        $avatar_url = $githubuser->getAvatarUrl($username);
        $stargazer = 0;

        if (!empty($repos)) {
          foreach ($repos as $repo) {
            //We need authentication
            $extra_info = $client->repos->get($username, $repo->getName());
            $stargazer += $extra_info->getStargazers();
          }
        }
       
        // Render each element as markup.
        $element[$delta] = [
          '#theme' => 'github_formatter',
          '#avatar' => $avatar_url,
          '#stargazer' => $stargazer,
          '#username' => $username,
        ];
      }

    } catch(\Exception $e) { }


    return $element;
  }

}
