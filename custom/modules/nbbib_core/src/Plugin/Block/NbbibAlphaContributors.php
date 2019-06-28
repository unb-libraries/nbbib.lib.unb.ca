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
    $text = '<table class="table"><thead><tr>';

    foreach (range('A', 'Z') as $letter) {
      $text .= '<th scope="col"><a href="/contributors?sort-initial=' .
        $letter . '">' . $letter .
        '</a></th>';
    }

    $text .= '</tr></thead></table>';

    return [
      '#markup' => $this->t($text),
    ];
  }

}
