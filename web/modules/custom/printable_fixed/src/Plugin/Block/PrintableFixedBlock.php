<?php

namespace Drupal\printable_fixed\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\node\NodeInterface;
use Drupal\printable\PrintableLinkBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Cache\Cache;

/**
 * Provides a printable links block for each printable entity.
 *
 * @Block(
 *   id = "printablefixedblock",
 *   admin_label = @Translation("Printable Fixed Block"),
 *   category = @Translation("Printable"),
 *   deriver = "Drupal\printable\Plugin\Derivative\PrintableLinksBlock"
 * )
 */
class PrintableFixedBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routematch;

  /**
   * The printable link builder.
   *
   * @var \Drupal\printable\PrintableLinkBuilderInterface
   */
  protected $linkBuilder;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    RouteMatchInterface $routematch,
    PrintableLinkBuilderInterface $link_builder,
    DateFormatter $dateFormatter
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routematch    = $routematch;
    $this->linkBuilder   = $link_builder;
    $this->dateFormatter = $dateFormatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match'),
      $container->get('printable.link_builder'),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

    $form += parent::buildConfigurationForm($form, $form_state);
    $period = [
      0,
      60,
      180,
      300,
      600,
      900,
      1800,
      2700,
      3600,
      10800,
      21600,
      32400,
      43200,
      86400,
    ];
    $period = array_map([
      $this->dateFormatter,
      'formatInterval',
    ], array_combine($period, $period));
    $period[0]                = '<' . $this->t('no caching') . '>';
    $period[Cache::PERMANENT] = $this->t('Forever');
    $form['cache']            = [
      '#type'  => 'details',
      '#title' => $this->t('Cache settings'),
    ];
    $form['cache']['max_age'] = [
      '#type'          => 'select',
      '#title'         => $this->t('Maximum age'),
      '#description'   => $this->t('The maximum time this block may be cached.'),
      '#default_value' => $period[0],
      '#options'       => $period,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $entity_type = $this->getDerivativeId();
    if (
      ($this->routematch->getMasterRouteMatch()->getParameter($entity_type))
      && ($node = Drupal::routeMatch()->getParameter('node'))
      && ($node instanceof NodeInterface)
    ) {
      return [
        '#theme' => 'links__entity__printable',
        '#links' => $this->linkBuilder->buildLinks($this->routematch->getMasterRouteMatch()
                                                                    ->getParameter($entity_type)),
        '#cache' => [
          'tags' => ['node:' . $node->id()],
        ],
      ];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    if (
      ($node = Drupal::routeMatch()->getParameter('node'))
      && $node instanceof NodeInterface
    ) {
      $nid = $node->id();
      return Cache::mergeTags(parent::getCacheTags(), ['node:' . $nid]);
    }

    return parent::getCacheTags();
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), ['route']);
  }

}
