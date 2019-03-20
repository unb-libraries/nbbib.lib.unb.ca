<?php

namespace Drupal\nbbib_core\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for Nbbib navigation.
 */
class NbbibCoreController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function home() {
    $element = [
      '#theme' => 'nbbib_intro',
      '#attributes' => [],
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function about() {
    $element = [
      '#theme' => 'nbbib_about',
      '#attributes' => [],
    ];

    return $element;
  }

}
