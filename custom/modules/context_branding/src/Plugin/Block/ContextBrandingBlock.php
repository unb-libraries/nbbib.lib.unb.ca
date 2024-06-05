<?php

namespace Drupal\context_branding\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Path\PathMatcher;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A custom dynamic block for site branding w/ non-link H1 frontpage title.
 *
 * @Block(
 *   id = "context_branding_block",
 *   admin_label = @Translation("Contextual Branding"),
 *   category = @Translation("Misc"),
 * )
 */
class ContextBrandingBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * For service dependency injection.
   *
   * @var Drupal\Core\Path\PathMatcher
   */
  protected $pathMatcher;

  /**
   * For service dependency injection.
   *
   * @var Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Class constructor.
   *
   * @param array $configuration
   *   The block configuration.
   * @param string $plugin_id
   *   The plugin identifier.
   * @param mixed $plugin_definition
   *   The plugin definition.
   * @param Drupal\Core\Path\PathMatcher $path_matcher
   *   Path matcher service dependency injection.
   * @param Drupal\Core\Config\ConfigFactory $config_factory
   *   Config factory service dependency injection.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    PathMatcher $path_matcher,
    ConfigFactory $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->pathMatcher = $path_matcher;
    $this->configFactory = $config_factory;
  }

  /**
   * Object create function.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Container interface.
   * @param array $configuration
   *   The block configuration.
   * @param string $plugin_id
   *   The plugin identifier.
   * @param mixed $plugin_definition
   *   The plugin definition.
   *
   * @return static
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('path.matcher'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $is_front = $this->pathMatcher->isFrontPage();
    $site_config = $this->configFactory->get('system.site');
    // $site_name = $site_config->get('name');
    $site_slogan = $site_config->get('slogan');
    $site_name = "
      <svg id='logo' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 52'>
        <g id='and'>
          <path d='M110.89,23.41c-1.21-.83-2.58-1.49-4.03-1.88,1.39-.81,3.05-1.76,4.98-2.89,4.53-2.62,5.13-7.01,3.7-10.2-1.39-3.1-5.01-5.52-9.16-4.08-.3.09-.47.42-.39.72l.65,2.47c.03.15.15.3.3.39.15.09.33.09.47.03,2.37-.86,4,.6,4.68,2.06.36.75,1.19,3.37-2.22,5.37-8.39,4.86-12.42,7.25-13.07,7.69-3.79,2.68-5.25,6.83-3.97,11.36,1.1,3.91,4.24,6.38,8.39,6.62h.59c4.77,0,7.56-3.49,7.79-6.95.27-3.4-1.78-6.2-4.83-6.62-.18,0-.36,0-.47.15-.12.12-.21.27-.21.45v2.71c0,.27.18.51.42.57,1.07.33,1.36,1.52,1.3,2.44-.12,1.67-1.39,3.55-4.39,3.4-2.49-.15-4.3-1.55-4.95-3.88-.8-2.89.09-5.52,2.49-7.22.09-.06.36-.24.92-.57,2.9-1.25,6.28-.92,8.86.89,2.7,1.88,4.03,5.1,3.82,9.03-.53,9.3-9.25,12.61-17.22,12.61-2.76,0-16.57-.6-17.19-12.46-.27-5.01,2.04-8.35,4-10.29,3.02-2.95,7.44-4.59,11.44-4.32.18.03.33-.03.44-.15.12-.12.18-.27.18-.45v-2.65c0-.3-.24-.57-.53-.6-3.85-.42-6.43-3.07-6.52-6.74-.06-1.88.53-3.52,1.66-4.68,1.27-1.31,3.14-2,5.42-2,2.1,0,3.82.69,4.98,2,1.04,1.19,1.54,2.83,1.36,4.47,0,.18.03.33.15.48.12.12.27.21.44.21h2.58c.3,0,.56-.24.59-.54.21-2.62-.62-5.22-2.28-7.1-1.3-1.49-3.73-3.28-7.82-3.28-3.29,0-6.16,1.13-8.12,3.16-1.87,1.94-2.82,4.5-2.73,7.45.09,2.98,1.3,5.69,3.41,7.63-2.76.92-5.25,2.44-7.32,4.47-3.59,3.52-5.42,8.2-5.16,13.21.62,11.87,11.23,16.07,20.98,16.07s20.33-4.23,21.01-16.22c.3-5.31-1.63-9.69-5.42-12.37h-.03Z' />
        </g>
        <g id='time'>
          <path d='M149.8,47.29s-.18-.3-.18-.57v-17.56c0-.21.06-.39.15-.51.09-.12.24-.18.41-.18h4.27c.36,0,.53.21.53.6v1.76c0,.18.06.3.18.3.06,0,.15-.06.24-.15,1.01-.92,1.96-1.64,2.9-2.15.95-.51,1.96-.75,3.02-.75,1.25,0,2.31.3,3.2.89.89.6,1.48,1.4,1.84,2.44,0,.09.06.15.15.15s.21-.06.33-.18c.89-.98,1.87-1.76,2.96-2.36,1.1-.6,2.19-.89,3.29-.89,1.66,0,2.99.51,3.97,1.55.98,1.04,1.48,2.44,1.48,4.23v12.7c0,.57-.3.86-.86.86h-3.94c-.27,0-.44-.06-.53-.15-.09-.12-.15-.3-.15-.57v-12.11c0-.78-.21-1.4-.65-1.85-.41-.45-1.04-.69-1.81-.69-.65,0-1.27.18-1.87.54-.59.36-1.13.78-1.54,1.28-.15.18-.24.33-.3.48-.06.15-.09.33-.09.54v11.66c0,.57-.3.86-.86.86h-3.91c-.24,0-.42-.06-.5-.15-.09-.09-.15-.3-.15-.57v-12.11c0-.78-.21-1.4-.65-1.85-.41-.45-1.04-.69-1.84-.69-.68,0-1.33.18-1.93.54-.59.36-1.21.92-1.9,1.67v12.31c0,.3-.06.51-.21.63-.15.12-.39.21-.71.21h-3.79c-.27,0-.45-.06-.56-.15v-.06Z' />
          <path d='M188.24,42.82c.89.83,1.99,1.22,3.32,1.22.86,0,1.63-.18,2.37-.57.71-.36,1.45-.98,2.19-1.85.09-.09.18-.15.3-.15.06,0,.18.03.35.12l2.55,1.07c.18.06.24.18.24.36,0,.12-.06.24-.15.36-1.13,1.61-2.37,2.77-3.68,3.43-1.3.66-2.93.98-4.86.98s-3.47-.42-4.95-1.22c-1.48-.81-2.61-1.94-3.44-3.37-.83-1.43-1.24-3.1-1.24-4.95,0-2,.42-3.79,1.27-5.31.86-1.55,2.02-2.71,3.47-3.55,1.45-.83,3.05-1.25,4.77-1.25s3.41.39,4.8,1.19c1.39.81,2.49,1.94,3.26,3.46.77,1.49,1.19,3.28,1.19,5.37,0,.3,0,.54-.09.66-.06.12-.24.18-.5.21h-12.09c-.3,0-.41.18-.41.57,0,1.34.44,2.42,1.33,3.25v-.03ZM194.04,36.02c.27,0,.41,0,.5-.09.06-.06.12-.21.12-.42,0-.63-.15-1.25-.41-1.85-.3-.63-.71-1.13-1.25-1.55-.53-.42-1.21-.6-1.99-.6-1.18,0-2.16.42-2.9,1.25-.77.83-1.13,1.91-1.1,3.22h7.08l-.06.03Z' />
          <path d='M144.85,28.5h-4.3c-.33,0-.59.27-.59.6v17.77c0,.33.26.6.59.6h4.3c.33,0,.59-.27.59-.6v-17.77c0-.33-.26-.6-.59-.6Z' />
          <path d='M144.82,19.62h-24.3c-.33,0-.59.27-.59.6v3.49c0,.33.27.6.59.6h8.5c.33,0,.59.27.59.6v21.97c0,.33.27.6.59.6h4.95c.33,0,.59-.27.59-.6v-21.97c0-.33.27-.6.59-.6h8.51c.33,0,.59-.27.59-.6v-3.49c0-.33-.27-.6-.59-.6h-.03Z' />
        </g>
        <g id='tide'>
          <path d='M41.46,46.72v-1.01c0-.18-.06-.3-.18-.3-.06,0-.18.06-.33.15-.68.66-1.45,1.19-2.25,1.61-.83.42-1.81.6-2.93.6-2.22,0-4.03-.83-5.39-2.5-1.36-1.67-2.04-4.06-2.04-7.19,0-1.97.36-3.7,1.04-5.22.71-1.52,1.63-2.68,2.82-3.52,1.19-.83,2.49-1.25,3.91-1.25.92,0,1.75.15,2.52.48.74.3,1.45.78,2.16,1.43.18.12.3.18.36.18.18,0,.24-.15.24-.48v-8.2c0-.42.18-.66.56-.66h4.33c.18,0,.3.06.41.18.12.12.15.3.15.51v25.11c0,.27-.06.45-.18.57-.12.12-.33.18-.65.18h-3.85c-.5,0-.74-.24-.74-.72l.06.06ZM39.77,32.11c-.53-.3-1.13-.42-1.81-.42-1.24,0-2.25.51-2.99,1.55-.74,1.04-1.1,2.65-1.1,4.8s.36,3.79,1.07,4.8c.71,1.01,1.69,1.52,2.96,1.52.95,0,1.78-.33,2.46-.95.68-.63,1.04-1.37,1.04-2.21v-7.81c-.53-.6-1.1-1.04-1.63-1.31v.03Z' />
          <path d='M56.51,42.82c.89.83,1.99,1.22,3.32,1.22.86,0,1.63-.18,2.37-.57.71-.36,1.45-.98,2.19-1.85.09-.09.18-.15.3-.15.06,0,.18.03.36.12l2.55,1.07c.18.06.24.18.24.36,0,.12-.06.24-.15.36-1.13,1.61-2.37,2.77-3.67,3.43-1.3.66-2.93.98-4.86.98s-3.47-.42-4.95-1.22c-1.48-.81-2.61-1.94-3.44-3.37-.83-1.43-1.24-3.1-1.24-4.95,0-2,.41-3.79,1.27-5.31.86-1.55,2.02-2.71,3.47-3.55,1.45-.83,3.05-1.25,4.77-1.25s3.41.39,4.8,1.19c1.39.81,2.49,1.94,3.26,3.46.77,1.49,1.19,3.28,1.19,5.37,0,.3-.03.54-.09.66-.06.12-.24.18-.5.21h-12.09c-.3,0-.41.18-.41.57,0,1.34.44,2.42,1.33,3.25v-.03ZM62.35,36.02c.27,0,.41,0,.5-.09.06-.06.12-.21.12-.42,0-.63-.15-1.25-.41-1.85-.3-.63-.71-1.13-1.24-1.55-.53-.42-1.22-.6-1.99-.6-1.19,0-2.16.42-2.9,1.25-.74.83-1.13,1.91-1.1,3.22h7.08l-.06.03Z' />
          <path d='M24.89,28.5h-4.3c-.33,0-.59.27-.59.6v17.77c0,.33.27.6.59.6h4.3c.33,0,.59-.27.59-.6v-17.77c0-.33-.27-.6-.59-.6Z' />
          <path d='M24.89,19.62H.59c-.33,0-.59.27-.59.6v3.49c0,.33.27.6.59.6h8.5c.33,0,.59.27.59.6v21.97c0,.33.27.6.59.6h4.95c.33,0,.59-.27.59-.6v-21.97c0-.33.27-.6.59-.6h8.5c.33,0,.59-.27.59-.6v-3.49c0-.33-.27-.6-.59-.6h-.03Z' />
        </g>
      </svg>
    ";

    if ($is_front) {
      $site_title = "
        <h1 class='site-title'>
          $site_name
        </h1>
      ";
    }
    else {
      $site_title = "
        <a href='/' title='Home' rel='home' class='site-title'>
          $site_name
        </a>
      ";
    }

    $markup = "
      <div class='contextual-region block block-system block-system-branding-block'>
        <div class='navbar-brand d-flex align-items-center'>
          <div>
            $site_title
            <div class='site-slogan'>
              $site_slogan
            </div>
          </div>
        </div>
      </div>
    ";

    return [
      '#markup' => $this->t($markup),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
