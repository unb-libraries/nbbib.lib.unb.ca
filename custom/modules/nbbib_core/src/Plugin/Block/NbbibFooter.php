<?php

namespace Drupal\nbbib_core\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a custom footer block.
 *
 * @Block(
 *   id = "nbbib_footer",
 *   admin_label = @Translation("NB Bibliography Footer"),
 *   category = @Translation("Misc"),
 * )
 */
class NbbibFooter extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $unb_logo = '/themes/custom/nbbib_lib_unb_ca/images/unb-libraries-white.png';
    $year = '2022';

    $text =
      "<div class='footer-row row clearfix'>

        <div id='footer-logo-div' class='col-md-6'>
          <a href='https://lib.unb.ca'>
            <img id='footer-logo-img' class='footer-logo left-block' alt='UNB Libraries logo'
            src='$unb_logo'>
          </a>
        </div>

        <div id='footer-copy' class='col-md-6'>
          <p>
            All contents © $year <a href='https://lib.unb.ca'>UNB Libraries</a>
          </p>
        </div>
      </div>";

    return [
      '#markup' => $this->t($text),
    ];
  }

}
