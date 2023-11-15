<?php

namespace Drupal\service_type\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Drupal\page_manager\Entity\Page;
use Drupal\page_manager\Entity\PageVariantViewBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Service type page controller.
 */
class ServiceTypeController extends ControllerBase {
  private const PAGE_NAME = 'service_type';

  /**
   * @var Drupal\page_manager\Entity\PageVariant
   */
  private $pageVariant;

  public function __construct() {
    $page = Page::load(self::PAGE_NAME);
    $this->pageVariant = $page->getVariant(
      $this->findVariant($page)
    );
  }

  /**
   * Replaces default contexts (county, service, parent service) for the ones that were sent in the request.
   * @param string $county
   * @param string $taxonomy
   * @param string $term
   * @param string $child
   *
   * @return array|RedirectResponse
   *
   * @throws \Exception
   */
  public function content(string $county, string $taxonomy, string $term, string $child) {
    $alias = '/' . $taxonomy . '/' . $term;
    $alias = empty($child) ? $alias : $alias . '/' . $child;
    // Get system path to get tid.
    $systemPath = Drupal::service('path_alias.manager')->getPathByAlias($alias);
    $homeUrl = \Drupal::urlGenerator()->generateFromRoute('<front>', [], ['absolute' => TRUE]);

    if ($systemPath !== $alias) {
      $tid = str_replace('/taxonomy/term/', '', $systemPath);
      $ancestors = Drupal::service('entity_type.manager')->getStorage('taxonomy_term')->loadAllParents($tid);
      $currentTerm = current($ancestors);
      $parentTermUuid = end($ancestors)->uuid();
      $this->setPageVariantStaticContext('service', $currentTerm->uuid());
      $this->setPageVariantStaticContext('service_parent', $parentTermUuid);

      $countyTerms = Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['name' => $county]);
      if (empty($countyTerms)) {
        if (strtolower($county) === 'all') {
          $this->setPageVariantStaticContext('county', strtolower($county));
        }
        else {
          return new RedirectResponse($homeUrl);
        }
      }
      else {
        $this->setPageVariantStaticContext('county', reset($countyTerms)->uuid());
      }

      $viewer = new PageVariantViewBuilder();
      $content = $viewer->view($this->pageVariant);
      $content['#title'] = $currentTerm->getName() . ' Services';

      return $content;
    }

    return new RedirectResponse($homeUrl);
  }

  /**
   * @param string $contextType
   * @param int|string $contextValue
   */
  private function setPageVariantStaticContext(string $contextType, $contextValue) {
    $context = $this->pageVariant->getStaticContext($contextType);
    $context['value'] = $contextValue;
    $this->pageVariant->setStaticContext($contextType, $context);
  }

  /**
   * @param Drupal\page_manager\Entity\Page $page
   *
   * @return string
   *
   * @throws \Exception
   */
  private function findVariant(Page $page): string {
    foreach (array_keys($page->getVariants()) as $variant) {
      if (strpos($variant, 'variant') !== FALSE) {
        return $variant;
      }
    }

    throw new \Exception('Unable to find a Service type page variant');
  }

}
