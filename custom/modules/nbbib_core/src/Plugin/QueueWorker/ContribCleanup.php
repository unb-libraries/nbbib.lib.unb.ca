<?php

namespace Drupal\nbbib_core\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
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
   * For service dependency injection. Access to entity management utilities.
   *
   * @var Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * For service dependency injection. Access to logger.
   *
   * @var Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $logger;

  /**
   * Constructor for ContribCleanup.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, LoggerChannelFactory $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->entityTypeManager = $entity_type_manager;
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
      $container->get('entity_type.manager'),
      $container->get('logger.factory')
    );
  }

  /**
   * Delete contributor if not present in any references.
   *
   * @param int $cid
   *   The id of the contributor being processed.
   */
  public function cleanupContrib($cid) {
    // Query for contributor in contributor->reference paragraphs.
    $matches = $this->typeManager->getStorage('paragraph')
      ->getQuery()
      ->condition('type', 'yabrm_bibliographic_contributor')
      ->condition('field_yabrm_contributor_person', $cid)
      ->execute();

    // If no matches, load contrib, delete, and log.
    if (empty($matches)) {
      $contrib = BibliographicContributor::load($cid);
      $contrib->delete();
      $this->logger->notice("Deleted orphan contributor [$cid]");
    }
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    parent::processItem($data);
  }

}
