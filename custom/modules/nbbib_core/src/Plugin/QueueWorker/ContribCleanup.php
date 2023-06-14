<?php

namespace Drupal\nbbib_core\Plugin\QueueWorker;

use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\yabrm\Entity\BibliographicContributor;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Deletes a bibliography contributor if no references credit them.
 *
 * @QueueWorker(
 *   id = "contrib_cleanup",
 *   title = @Translation("Orphan contributor cleanup."),
 *   cron = {"time" = 60}
 * )
 */
class ContribCleanup extends QueueWorkerBase implements ContainerFactoryPluginInterface {
  /**
   * The logger service.
   *
   * @var Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $logger;

  /**
   * Constructor for ContribCleanup.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelFactory $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->logger = $logger->get('nbbib_core');
  }

  /**
   * Creates a new ContribCleanup object.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    // Delete orphan contrib and log.
    $cid = $data->cid;
    $contrib = BibliographicContributor::load($cid);

    if ($contrib) {
      $contrib->delete();
      $this->logger->notice("Cleanup: Deleted orphan contributor [$cid].");
    }
  }

}
