<?php

namespace Drupal\nbbib_core\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\search_api\Entity\Index;

/**
 * Controller for post-processing of contributor merge operations.
 */
class MergeContribsController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function waitReindex($yabrm_contributor) {
    // Get index status. How many items are pending reindex.
    $todo = Index::load('references_nbbib_lib_unb_ca')
      ->getTrackerInstance()
      ->getRemainingItemsCount();

    // Wait for reindexing.
    while ($todo > 0) {
      $todo = Index::load('references_nbbib_lib_unb_ca')
        ->getTrackerInstance()
        ->getRemainingItemsCount();
    }

    // Wait for refresh.
    sleep(3);

    return $this->redirect('entity.yabrm_contributor.canonical', [
      'yabrm_contributor' => $yabrm_contributor,
    ]);
  }

}
