<?php

namespace Drupal\github;

use Drupal\Core\Config\ConfigFactory;
use Github;

/**
 * Implement a custom service to communication with Github.
 */
class GithubGetClient {
  protected $client;

  /**
   * On construct asign github api token.
   */
  public function __construct(ConfigFactory $config_factory) {
    $config = $config_factory->get('github.settings');
    $api_token = $config->get('token');

    $client = new \Github\Client();

    // Add api token to allow access to some specific method.
    // You can check methods required
    // authentication in https://developer.github.com/v3/
    if ($api_token) {
      $client->authenticate($api_token, NULL, Github\Client::AUTH_HTTP_TOKEN);
    }

    // Assign the object client.
    $this->client = $client;
  }

  /**
   * Return client object to do calls over github API.
   */
  public function githubGetClient() {
    return $this->client;
  }

}
