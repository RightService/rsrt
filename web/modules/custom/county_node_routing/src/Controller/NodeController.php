<?php

declare(strict_types=1);

namespace Drupal\county_node_routing\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\node\Controller\NodeViewController;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NodeController extends ControllerBase {

  private $em;

  private $renderer;

  private $user;

  private $repository;

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('renderer'),
      $container->get('entity.repository'),
      $container->get('current_user')
    );
  }

  public function __construct(
    EntityTypeManagerInterface $entityManager,
    RendererInterface          $renderer,
    EntityRepositoryInterface  $repository,
    AccountInterface           $currentUser = NULL
  ) {
    $this->em = $entityManager;
    $this->renderer = $renderer;
    $this->repository = $repository;
    $this->user = $currentUser;
  }

  public function view(EntityInterface $node, $viewMode = 'full', $langcode = NULL) {
    $controller = new NodeViewController($this->em, $this->renderer, $this->user, $this->repository);

    return $controller->view($node, $viewMode, $langcode);
  }

}
