<?php
/**
 * @file
 * Contains \Drupal\alias_formatter\Plugin\field\formatter\AliasFormatter.
 */

namespace Drupal\alias_formatter\Plugin\Field\FieldFormatter;

use Drupal;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\taxonomy\Entity\Term;
use Exception;

/**
 * Plugin implementation of the 'alias_formatter' formatter.
 *
 * @FieldFormatter(
 *   id ="alias_formatter",
 *   label = @Translation("Alias Formatter"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class AliasFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $target_id = $item->get('target_id')->getValue();
      $term      = Term::load($target_id);
      if ($term) {
        $bundle           = $term->bundle();
        $url              = $this->getAlias($term, $bundle);
        $title            = $term->getName();
        $elements[$delta] = [
          '#theme'    => 'alias_formatter',
          '#url'      => $url,
          '#langcode' => $langcode,
          '#title'    => $title,
        ];
      }
    }

    return $elements;
  }

  private function getAlias($term, $bundle) {
    switch ($bundle) {
      case 'service_type':
        return $this->getAliasServiceType($term);
        break;
      case 'county':
        return $this->getAliasCounty($term);
        break;
      default:
        return $this->getAliasDefault($term);
        break;
    }
  }

  private function getAliasServiceType($term) {
    $aliases = $this->getAllAliases($term);
    $alias   = '';

    if (!empty($aliases)) {
      foreach ($aliases as $a) {
        if (strpos($a->getAlias(), '/services/') !== FALSE) { //get the only one containing /services/
          $alias = $a->getAlias();
          break;
        }
      }
    }

    if ($alias) { //override only if alias is avaiable
      $county = Drupal::service('default_county')->getCounty();
      return '/service-type/' . $county . $alias;
    }

    return '/taxonomy/term/' . $term->id();
  }

  private function getAliasCounty($term) {
    $aliases = $this->getAllAliases($term);

    if (!empty($aliases)) { //override only if alias is avaiable
      $alias = array_shift($aliases);
      return '/servicetypes/' . $alias->getAlias();
    }

    return '/servicetypes/' . strtolower($term->getName());
  }

  private function getAliasDefault($term) {
    $aliases = $this->getAllAliases($term);

    if (!empty($aliases)) { //override only if alias is avaiable
      $alias = array_shift($aliases);
      return $alias->getAlias();
    }

    return '/taxonomy/term/' . $term->id();
  }

  private function getAllAliases($term) {
    $tid = $term->id();
    try {
      return Drupal::entityTypeManager()
                   ->getStorage('path_alias')
                   ->loadByProperties(['path' => '/taxonomy/term/' . $tid]);
    }
    catch (Exception $e) {
      Drupal::logger('exception')->error($e->getMessage());
      return [];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary   = [];
    $summary[] = $this->t('Overrides taxonomy url to its alias with additional path included');
    return $summary;
  }

}
