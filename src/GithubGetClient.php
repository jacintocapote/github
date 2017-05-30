<?php

/**
 * @file
 * Contains \Drupal\github\GithubGetClient.
 */

namespace Drupal\github;

use Drupal\Core\Config\ConfigFactory;

class GithubGetClient {
  protected $client;

  /**
   * On construct asign github api token.
   */
  public function __construct(ConfigFactory $config_factory) {
    github_load_library();
    $config = $config_factory->get('github.settings');
    $api_token = $config->get('token');

    $client = new \GithubClient();
    $client->setAuthType(\GitHubClientBase::GITHUB_AUTH_TYPE_OAUTH_BASIC);
    $client->setOauthKey($api_token);
    $this->client = $client;
  }

  /**
   * Return client object to do calls over github API.
   */
  public function GithubGetClient() {
    return $this->client;
  }

  public function getServiceExampleValue() {
    return 'hola';
  }
}
