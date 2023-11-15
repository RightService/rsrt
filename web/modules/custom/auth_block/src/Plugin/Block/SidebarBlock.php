<?php

namespace Drupal\auth_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;

/**
 * Provides a custom sidebar auth block
 *
 * @Block(
 *   id = "sidebar_block",
 *   admin_label = @Translation("Custom authorisation block"),
 * )
 */
class SidebarBlock extends BlockBase implements BlockPluginInterface{
  /**
   * {@inheritdoc}
   */
  public function build() {
    
    return array(
        '#theme' => 'auth_block__sidebar',
        '#title' => 'ARE YOU A SERVICE PROVIDER OR LIBRARY?',
        '#description' => 'Register here.'
      );
  }

}