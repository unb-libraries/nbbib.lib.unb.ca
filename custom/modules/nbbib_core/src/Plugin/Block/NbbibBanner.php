<?php

namespace Drupal\nbbib_core\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a banner block.
 *
 * @Block(
 *   id = "nbbib_banner",
 *   admin_label = @Translation("NB Bibliography Banner"),
 *   category = @Translation("Misc"),
 * )
 */
class NbbibBanner extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $text =
      '<div id="nbbib-banner"></div>';
    return [
      '#markup' => $this->t($text),
    ];
  }

}
