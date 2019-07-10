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
    $text = '<nav class="pager-nav text-center" role="navigation"">' .
      '<ul class="pagination js-pager__items">';

    foreach (range('A', 'Z') as $letter) {
      $text .= '<li class="pager__item initial-' . $letter . '">
        <a href="/contributors?sort-initial='
          . $letter . '" class="alpha-page">' . $letter . '</a></li>';
    }

    $text .= '</ul></nav>';

    return [
      '#markup' => $this->t($text),
      '#attached' => [
        'library' => [
          'nbbib_core/contrib-alpha-pager',
        ],
      ],
    ];
  }

}
