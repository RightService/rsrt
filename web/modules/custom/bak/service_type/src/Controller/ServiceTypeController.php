<?php

namespace Drupal\service_type\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\views\Views;
use Drupal\taxonomy\Entity\Term;

class ServiceTypeController extends ControllerBase {

  public function content($county, $taxonomy, $term, $child) {
    $alias = '/'.$taxonomy.'/'.$term;
    $alias = empty($child) ? $alias : $alias.'/'.$child;
    $system_path = \Drupal::service('path_alias.manager')->getPathByAlias($alias); //get system path to get tid

    if ($system_path != $alias){
      $tid = str_replace('/taxonomy/term/', '', $system_path);
      $ancestors = \Drupal::service('entity_type.manager')->getStorage("taxonomy_term")->loadAllParents($tid); //get parent term
      $current_term = current($ancestors);
      $current_uuid = $current_term->uuid();
      $parent_uuid = end($ancestors)->uuid();

      $county_terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadByProperties(array('name' => $county));
      $county_term = reset($county_terms);
      $county_term_uuid = $county_term->uuid();
      $GLOBALS['county'] = $county;

      $page = \Drupal\page_manager\Entity\Page::load('service_type');
      $variants = $page->getVariants();
      $variantIds = array_keys($variants);
      $pageVariant = $page->getVariant("service_type-panels_variant-0");

      $context = $pageVariant->getStaticContext('service');
      $context['value'] = $current_uuid; //replace default term for a current one
      $pageVariant->setStaticContext('service', $context);

      $context = $pageVariant->getStaticContext('service_parent');
      $context['value'] = $parent_uuid; //replace default term for a current or parent one
      $pageVariant->setStaticContext('service_parent', $context);

      $context = $pageVariant->getStaticContext('county');
      $context['value'] = $county_term_uuid; //replace default term for a current or parent one
      $pageVariant->setStaticContext('county', $context);

      $viewer = new \Drupal\page_manager\Entity\PageVariantViewBuilder();
      $content = $viewer->view($pageVariant);
      $content['#title'] = $current_term->getName() . ' Services';
      return $content;
    }
  }

}