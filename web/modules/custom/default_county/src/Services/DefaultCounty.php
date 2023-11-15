<?php

namespace Drupal\default_county\Services;

use Drupal;
use Drupal\taxonomy\Entity\Term;
use Drupal\user\Entity\User;


/**
 * Default county service's class.
 */
class DefaultCounty {
  public const DEFAULT_COUNTY = 'all';

  /**
   * @return string
   */
  public function getDefaultCounty(): string {
    $countyList = $this->getUserCountyList();
    $county_id  = array_shift($countyList);

    if ($county_id) {
      $term = Term::load($county_id);
      if ($term) {
        return strtolower($term->getName());
      }
    }

    if (isset($_COOKIE['county'])) {
      return $_COOKIE['county'];
    }

    return self::DEFAULT_COUNTY;
  }

  /**
   * @return array
   */
  public function getUserCountyList(): array {
    $user = User::load(Drupal::currentUser()->id());

    if ($user) {
      $counties = $user->field_counties->getValue();
      return array_column($counties, 'target_id');
    }

    return [];
  }

  /**
   * @return string
   */
  public function getCounty(): string {
    $session   = Drupal::request()->getSession();
    $countyUrl = Drupal::request()->get('county');
    if ($session) {
      $sessionValue = $session->get('county');
      if (!isset($sessionValue) || ($sessionValue !== $countyUrl)) {
        $session->set('county',
          empty($countyUrl) ? $this->getDefaultCounty() : $countyUrl);
      }
    }

    return $session->get('county');
  }

  /**
   * Renders county select html for county select popup.
   *
   * @return string
   */
  public function renderCountySelect(): string {
    $options = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('county');
    $module_path = \Drupal::moduleHandler()->getModule('default_county')->getPath();

    $markup = twig_render_template($module_path . '/templates/county_select_modal.html.twig', [
      'options' => $options,
      // Needed to prevent notices when Twig debugging is enabled.
      'theme_hook_original' => 'not-applicable',
    ]);
    // Cast to string since twig_render_template returns a Markup object.
    return (string) $markup;
  }

}
