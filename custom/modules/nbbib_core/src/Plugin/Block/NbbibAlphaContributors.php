<?php

namespace Drupal\nbbib_core\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an alpha pager block for contributors.
 *
 * @Block(
 *   id = "nbbib_alpha_contributors",
 *   admin_label = @Translation("NB Bibliography Alpha Contributors"),
 *   category = @Translation("Misc"),
 * )
 */
class NbbibAlphaContributors extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    return [
      '#attached' => [
        'library' => [
          'nbbib_core/contrib-alpha-pager',
        ],
      ],
    ];
  }

}
