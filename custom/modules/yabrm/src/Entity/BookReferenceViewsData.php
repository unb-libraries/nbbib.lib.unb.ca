<?php

namespace Drupal\yabrm\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Book reference entities.
 */
class BookReferenceViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
