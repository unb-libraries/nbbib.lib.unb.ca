<?php

namespace Drupal\nbbib_core\Plugin\QueueWorker;

use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Deletes a bibliography archive if no references name them.
 *
 * @QueueWorker(
 *   id = "archive_cleanup_hard",
 *   title = @Translation("Orphan archive cleanup (DELETE)."),
 *   cron = {"time" = 60}
 * )
 */
class ArchiveCleanupHard extends QueueWorkerBase implements ContainerFactoryPluginInterface {
  /**
   * The logger service.
   *
   * @var Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $logger;

  /**
   * Constructor for ArchiveCleanup.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelFactory $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->logger = $logger->get('nbbib_core');
  }

  /**
   * Creates a new ArchiveCleanup object.
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
    // Delete orphan term and log.
    $tid = $data->tid;
    $archive = Term::load($tid);

    if ($archive) {
      $archive->delete();
      $this->logger->notice("Cleanup: Deleted orphan archive [$tid].");
    }
  }

}
