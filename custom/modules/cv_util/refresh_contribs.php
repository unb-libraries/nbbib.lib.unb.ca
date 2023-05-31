<?php

/**
 * @file
 * Contains refresh_contribs.php.
 *
 * Re-saves contributors for nbbib.lib.unb.ca.
 */

use Drupal\yabrm\Entity\BibliographicContributor;

refresh_contribs();

/**
 * Loads and saves all entities from a given type.
 */
function refresh_contribs() {

  $ids = \Drupal::entityQuery('yabrm_contributor')
    ->execute();

  if (!empty($ids)) {
    foreach ($ids as $id) {
      $contrib = BibliographicContributor::load($id);

      if ($contrib) {
        $title = $contrib->getName();
        $contrib->save();
        echo "[-] [$title]->[Updated]\n";
      }
    }
  }
}
