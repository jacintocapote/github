<?php

/**
 * @file
 * Contains \Drupal\github\GithubGetClient.
 */

namespace Drupal\github;

use Drupal\Core\Config\ConfigFactory;
use Github;

class GithubGetClient {
  protected $client;

  /**
   * On construct asign github api token.
   */
  public function __construct(ConfigFactory $config_factory) {
    $config = $config_factory->get('github.settings');
    $api_token = $config->get('token');

    $client = new \Github\Client();
    $client->authenticate($api_token, NULL, Github\Client::AUTH_HTTP_TOKEN);
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
