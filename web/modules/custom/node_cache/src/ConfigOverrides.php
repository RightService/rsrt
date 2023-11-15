<?php

namespace Drupal\node_cache;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\StorageInterface;


class ConfigOverrides implements ConfigFactoryOverrideInterface {

  /**
   * {@inheritdoc}
   */
  public function loadOverrides($names) {
    $overrides = array();
    if (in_array('statistics.settings', $names)) {
      $overrides['statistics.settings'] = ['display_max_age' => 0]; //disable cache for statistics module counter
    }
    return $overrides;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheSuffix() {
    return 'ConfigOverrider';
  }
  
  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata($name) {
    return new CacheableMetadata();
  }

  /**
   * {@inheritdoc}
   */
  public function createConfigObject($name, $collection = StorageInterface::DEFAULT_COLLECTION) {
    return NULL;
  }

}