<?php

namespace Drupal\default_county\Services;

use Drupal;
use Drupal\taxonomy\Entity\Term;
use Drupal\user\Entity\User;

class DefaultCounty {

  public function getDefaultCounty() {
    $countyList = $this->getUserCountyList();
    $county_id  = array_shift($countyList);

    if ($county_id) {
      $term = Term::load($county_id);
      if ($term) {
        return strtolower($term->getName());
      }
    }

    return 'bay';
  }

  public function getUserCountyList() {
    $user = User::load(Drupal::currentUser()->id());

    if ($user) {
      $counties = $user->field_counties->getValue();
      return array_column($counties, 'target_id');
    }

    return [];
  }

  public function getCounty() {
    $session   = Drupal::request()->getSession();
    $countyUrl = Drupal::request()->get('county');

    if ($session) {
      $sessionValue = $session->get('county');
      if (!isset($sessionValue) || ($sessionValue !== $countyUrl)) {
        $session->set('county',
          empty($countyUrl) ? $this->getDefaultCounty() : $countyUrl); //default county
      }
    }

    return $session->get('county');
  }

}
