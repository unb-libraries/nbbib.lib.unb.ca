<?php

namespace Drupal\nbbib_core\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a blank block for spacing.
 *
 * @Block(
 *   id = "nbbib_blank",
 *   admin_label = @Translation("NBBIB Blank"),
 *   category = @Translation("Misc"),
 * )
 */
class NbbibBlank extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => "",
    ];
  }

}
