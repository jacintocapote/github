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

  public function TenLastWeek() {
    return [
      '#markup' => $this->serviceGithub->getServiceExampleValue()
    ];
  }

  public function TenHottest() {
    return [
      '#markup' => $this->serviceGithub->getServiceExampleValue()
    ];
  }

  public function Pepper() {
    return [
      '#markup' => $this->serviceGithub->getServiceExampleValue()
    ];
  }

}
