<?php

namespace Drupal\nbbib_core\Plugin\QueueWorker;

use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Deletes a bibliography topic if no references name them.
 *
 * @QueueWorker(
 *   id = "topic_cleanup",
 *   title = @Translation("Orphan topics cleanup."),
 *   cron = {"time" = 60}
 * )
 */
class TopicCleanup extends QueueWorkerBase implements ContainerFactoryPluginInterface {
  /**
   * The logger service.
   *
   * @var Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $logger;

  /**
   * Constructor for TopicCleanup.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelFactory $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->logger = $logger->get('nbbib_core');
  }

  /**
   * Creates a new TopicCleanup object.
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
    $topic = Term::load($tid);

    if ($topic) {
      $topic->set('status', FALSE);
      $topic->save();
      $this->logger->notice("Cleanup: Unpublished orphan topic [$tid].");
    }
  }

}
