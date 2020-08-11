<?php

namespace Drupal\unb_lib_branding_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides 'UNB Libraries Branding' block.
 *
 * @Block(
 *   id = "unb_lib_branding",
 *   admin_label = @Translation("UNB Libraries Branding")
 * )
 */
class UnbLibBranding extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['label_display' => FALSE];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = \Drupal::config('system.site');

    $site_logo = $config->get('logo');
    $site_name = $config->get('name');
    $site_slogan = $config->get('slogan');

    $renderable = [
      '#theme' => 'unb_lib_branding',
      '#site_logo' => $site_logo,
      '#site_name' => $site_name,
      '#site_slogan' => $site_slogan,
    ];

    return $renderable;
  }

}
