<?php

/**
 * @file
 * Contains refresh_paragraphs.php.
 *
 * Re-saves contributors for nbbib.lib.unb.ca.
 */

use Drupal\yabrm\Entity\BibliographicContributor;

refresh_entities('paragraph');

/**
 * Loads and saves all entities from a given type.
 */
function refresh_entities($type) {

  $ids = \Drupal::entityQuery('yabrm_contributor')
    ->accessCheck(FALSE)
    ->execute();

  if (!empty($ids)) {
    foreach ($ids as $id) {
      $entity = Drupal::entityTypeManager()->getStorage($type)->load($id);

      if ($entity) {
        $entity->setNewRevision(TRUE);
        $entity->save();
        echo "[-] [$type][$id]->[Updated]\n";
      }
    }
  }
}
