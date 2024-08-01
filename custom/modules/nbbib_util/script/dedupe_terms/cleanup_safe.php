<?php

/**
 * @file
 * Contains dedupe_safe.php
 */

use Drupal\yabrm\Entity\BookReference;
use Drupal\yabrm\Entity\BookSectionReference;
use Drupal\yabrm\Entity\JournalArticleReference;
use Drupal\yabrm\Entity\ThesisReference;

cleanup_terms('nbbib_residences');
cleanup_terms('nbbib_archives');
cleanup_terms('nbbib_reference_topics');
cleanup_terms('nbbib_locations');
 
function cleanup_terms(string $vid) {
  // Query unique terms.
  $query = \Drupal::database()->query(
    "SELECT min(tid) as tid, name
    FROM taxonomy_term_field_data
    WHERE vid = '$vid'
    GROUP BY name"
  );

  $set = $query->fetchAll();
  $merges = [];
  // Iterate through first instances of terms.
  foreach($set as $term) {
    $tid = $term->tid;
    $name = $term->name;
    // Escape characters for injecting into SQL.
    $name = str_replace("'", "\'", $name);
    $name = str_replace(",", "\,", $name);
    // Remove semicolons (skips all records that contain them).
    $name = str_replace(";", "", $name);

    // Query for duplicates.
    $query = \Drupal::database()->query(
      "SELECT tid
      FROM taxonomy_term_field_data 
      WHERE name = '$name'
      AND tid <> $tid"
    );

    $dupes = $query->fetchAll();
    // If there are duplicates...
    if (!empty($dupes)) {
      $dupe_ids = [];
      // Iterate through duplicates and build array of mergeable ids.
      foreach ($dupes as $dupe) {
        $dupe_ids[] = $dupe->tid;
      }

      $merges[] = [
        'target' => $tid,
        'merged' => $dupe_ids,
      ];
    }
  }

  // Iterate through merges.
  $merged = array_values(array_column($merges, 'merged'));
  $ids = [];
  foreach ($merged as $merge) {
    $ids = array_merge($ids, $merge);
  }
  
  if (!empty($ids)) {
    $handler = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
    $entities = $handler->loadMultiple(
      \Drupal::entityQuery('taxonomy_term')
        ->condition('vid', $vid)
        ->condition('tid', $ids, 'IN')
        ->accessCheck(FALSE)
        ->execute()
    );
    foreach ($entities as $entity) {
      $id = $entity->id();
      echo "Deleting merged $vid [$id]\n";
    }
    // $handler->delete($entities);
    // drupal_flush_all_caches();
  }
}

