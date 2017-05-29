<?php

/**
 * @file
 * Contains \Drupal\github\GithubGetClient.
 */

namespace Drupal\github;

class GithubGetClient {
  protected $client;

  /**
   * On construct asign github api token.
   */
  public function __construct() {
    //Pending read from variable and assing value.
    $api_token = '4b432961af4f7ca2566e00670f5fb5239c3c39d8';
    github_load_library();
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
}
