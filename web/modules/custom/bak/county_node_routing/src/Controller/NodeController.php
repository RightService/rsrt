<?php

namespace Drupal\county_node_routing\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Controller\NodeViewController;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NodeController extends ControllerBase
{
    private $em;
    private $renderer;
    private $user;

    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('entity.manager'),
            $container->get('renderer'),
            $container->get('current_user')
        );
    }

    public function __construct(EntityManagerInterface $entityManager, RendererInterface $renderer, AccountInterface $currentUser = null)
    {
        $this->em = $entityManager;
        $this->renderer = $renderer;
        $this->user = $currentUser;
    }

    public function view(EntityInterface $node, $viewMode = 'full', $langcode = null)
    {
        $controller = new NodeViewController($this->em, $this->renderer, $this->user);

        return $controller->view($node, $viewMode, $langcode);
    }
}
