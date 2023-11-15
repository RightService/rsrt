<?php

namespace Drupal\library_dashboard\Controller;

use Drupal;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Controller\ControllerBase;
use Drupal\page_manager\Entity\Page;
use Drupal\page_manager\Entity\PageVariantViewBuilder;
use Drupal\views\Views;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class LibraryDashboardController extends ControllerBase {

  /**
   * @param $county
   *
   * @return array|Response
   * @throws InvalidPluginDefinitionException
   * @throws PluginNotFoundException
   */
  public function content($county) {
    if (empty($county)) {
      $this->countyRedirect($county);
      return new Response('');
    }
    $county_term_uuid = $this->getTermUuid($county);

    $page = Page::load('library_dashboard');
    $pageVariant = $page->getVariant("library_dashboard-panels_variant-0");

    $context = $pageVariant->getStaticContext('county');
    $context['value'] = $county_term_uuid; //replace default county
    $pageVariant->setStaticContext('county', $context);

    $viewer = new PageVariantViewBuilder();
    return $viewer->view($pageVariant);
  }

  /**
   * @param $page_url
   * @param $county
   *
   * @return array|Response
   * @throws InvalidPluginDefinitionException
   * @throws PluginNotFoundException
   */
  public function pages($page_url, $county) {
    if (empty($county)) {
      $this->countyRedirect($county);
      return new Response('');
    }
    $county_term_id = $this->getTermId($county);
    $page_name = str_replace(['-', ' '], '_', strtolower($page_url));

    $view = Views::getView($page_name);
    if ($view === NULL) {
      throw new NotFoundHttpException();
    }
    $view->setArguments([$county_term_id]);
    $view->setDisplay('page_1');
    $view->preExecute();
    $view->execute();

    switch ($page_url) {
      case 'new-services':
        $view->setTitle("New Services in " . ucfirst($county) . " County");
        break;
      case 'recently-updated-providers-and-services':
        $view->setTitle("Recently Updated Providers and Services in " . ucfirst($county) . " County");
        break;
    }

    return $view->buildRenderable('page_1', [$county_term_id]);
  }

  /**
   * @param $name
   *
   * @return string|null
   * @throws InvalidPluginDefinitionException
   * @throws PluginNotFoundException
   */
  private function getTermUuid($name): string {
    $county_terms = Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties(['name' => $name]);
    $county_term = reset($county_terms);

    if ($county_term) {
      return $county_term->uuid();
    }

    return '';
  }

  /**
   * @param $name
   *
   * @return int|null
   * @throws InvalidPluginDefinitionException
   * @throws PluginNotFoundException
   */
  private function getTermId($name): int {
    $county_terms = Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties(['name' => $name]);
    $county_term = reset($county_terms);

    if ($county_term) {
      return $county_term->id();
    }

    return 0;
  }

  protected function countyRedirect($county): void {
    $path = Drupal::service('path.current')->getPath();
    if (empty($county)) {
      $county = Drupal::service('default_county')->getDefaultCounty();
    }
    $response = new RedirectResponse($path . '/' . $county);
    $response->send();
  }

}
