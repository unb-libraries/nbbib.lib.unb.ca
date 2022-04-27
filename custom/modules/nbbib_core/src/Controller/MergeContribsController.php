<?php

namespace Drupal\nbbib_core\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\search_api\Entity\Index;

/**
 * Controller for post-processing of contributor merge operations.
 */
class MergeContribsController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function waitReindex($yabrm_contributor) {
    $batch = [
      'operations' => [
        ['Drupal\nbbib_core\Controller\MergeContribsController::monitorReindex'],
      ],
      'finished' => 'Drupal\nbbib_core\Controller\MergeContribsController::finalize',
      'title' => $this->t('Merging contributors'),
      'init_message' => $this->t('Contributor merge reindex is starting.'),
      'progress_message' => $this->t('Processing...'),
      'error_message' => $this->t('Contributor merge has encountered an error.'),
    ];

    $redirect = URL::fromRoute('entity.yabrm_contributor.canonical', [
      'yabrm_contributor' => $yabrm_contributor,
    ]);

    batch_set($batch);
    return batch_process($redirect);
  }

  /**
   * {@inheritdoc}
   */
  private static function monitorReindex($context) {
    // Get index status. How many items are pending reindex.
    $todo = Index::load('references_nbbib_lib_unb_ca')
      ->getTrackerInstance()
      ->getRemainingItemsCount();

    \Drupal::service('messenger')->addMessage("$todo");

    $context['finished'] = $todo == 0;
  }

}
