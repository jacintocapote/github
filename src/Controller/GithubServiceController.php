<?php

/**
 * @file
 * Contains \Drupal\github\GithubServiceController.
 */

namespace Drupal\github\Controller;

Use Drupal\github\GithubGetClient;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

class GithubServiceController extends ControllerBase {

  /**
   * @var \Drupal\github\GithubGetClient
   */
  protected $serviceGithub;

  /**
   * @var GuzzleHttp\Client
   */
  protected $http_client;

  /**
   * {@inheritdoc}
   */
  public function __construct(GithubGetClient $serviceGithub, ClientInterface $http_client) {
    //We don't need http client for anything because we are using external library. But is injected to show as example.
    $this->serviceGithub = $serviceGithub;
    $this->http_client = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('github.githubgetclient'),
      $container->get('http_client')
    );
  }

  /**
   * Routing callback to show top 10 issues.
   */
  public function TenLastWeek() {
    $client = $this->serviceGithub->GithubGetClient();

    //Do a search to get 10 issues, created in the last week, with the most comments for PHP, Javascript, Ruby
    //With setPerPage we improve performance to get only first 10 items.
    $created = date('Y-m-d', strtotime('-1 week'));
    $issues = $client->api('search')->setPerPage(10)->issues('type:issue language:php language:javascript language:ruby created:">' . $created  . '"', 'comments', 'desc');

    return [
      '#theme' => 'github_issues',
      '#issues' => $issues['items'],
    ];
  }

  /**
   * Routing callback to show top 10 repos.
   */
  public function TenHottest() {
    $client = $this->serviceGithub->GithubGetClient();

    //Do a search to get 10 repos (hottest), created in the last week for PHP, Javascript, Ruby
    //With setPerPage we improve performance to get only first 10 items.
    $created = date('Y-m-d', strtotime('-1 week'));
    $repos = $client->api('search')->setPerPage(10)->repositories('language:php language:javascript language:ruby created:">' . $created  . '"', 'stars', 'desc');

    return [
      '#theme' => 'github_repos',
      '#repos' => $repos['items'],
    ];
  }

  /**
   * Routing callback to show a report for Mrs. Pepper Pots
   */
  public function Pepper() {
    $client = $this->serviceGithub->GithubGetClient();

    //Do a search to get 10 repos (hottest), created in the last week
    //With setPerPage we improve performance to get only first 10 items.
    $created = date('Y-m-d', strtotime('-1 week'));
    $repos = $client->api('search')->setPerPage(10)->repositories('created:">' . $created  . '"', 'stars', 'desc');
    $report = $this->PepperCalculateFunding($repos['items']);

    return [
      '#theme' => 'github_funding',
      '#report' => $report,
    ];
  }

  private function PepperCalculateFunding($repos) {
    $data = [];
 
    foreach ($repos as $repo) {
      $project = [
        'name' => $repo['name'],
        'url' => $repo['html_url'],
        'stars' => $repo['stargazers_count'],
        'language' => $repo['language'],
        'watcher' => $repo['watchers'],
        'fork' => $repo['forks_count'],
        'wiki' => ($repo['has_wiki'] ? 50 : 0), 
        'downloaded' => ($repo['has_downloads'] ? 100 : 0),
        'issues' => ($repo['has_issues'] ? 10 : 0),
      ];

      //Total will be calculate on the template.
      $data[] = $project;
    }

    return $data;
  }

}
