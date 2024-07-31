<?php

/**
 * @file
 * Contains dedupe_safe.php
 */

 use Drupal\yabrm\Entity\BookReference;
 use Drupal\yabrm\Entity\BookSectionReference;
 use Drupal\yabrm\Entity\JournalArticleReference;
 use Drupal\yabrm\Entity\ThesisReference;

 dedupe_terms('nbbib_residences', 'yabrm_contributor', 'nb_residences');
 dedupe_terms('nbbib_archives', 'yabrm_book', 'archive');
 dedupe_terms('nbbib_archives', 'yabrm_book_section', 'archive');
 dedupe_terms('nbbib_archives', 'yabrm_journal_article', 'archive');
 dedupe_terms('nbbib_archives', 'yabrm_thesis', 'archive');
 dedupe_terms('nbbib_reference_topics', 'yabrm_book', 'topics');
 dedupe_terms('nbbib_reference_topics', 'yabrm_book_section', 'archive');
 dedupe_terms('nbbib_reference_topics', 'yabrm_journal_article', 'archive');
 dedupe_terms('nbbib_reference_topics', 'yabrm_thesis', 'archive');
 dedupe_terms('nbbib_locations', 'yabrm_contrib_archival', 'field_location');
 
function dedupe_terms(string $vid, string $type, string $field) {
  // Query unique terms.
  $query = \Drupal::database()->query(
    "SELECT tid, name
    FROM taxonomy_term_field_data
    WHERE vid = '$vid'
    GROUP BY name"
  );

  $set = $query->fetchAll();
  $merges = [];
  // Iterate through unique terms.
  foreach($set as $term) {
    $tid = $term->tid;
    // Escape quotation marks for injecting into SQL.
    $name = str_replace("'", "\'", $term->name);

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
  foreach ($merges as $merge) {
    $target = $merge['target'];
    
    foreach($merge['merged'] as $merged) {
      // Process entities.
      $ids = \Drupal::entityQuery($type)
        ->condition($field, $merged, 'IN')
        ->accessCheck(FALSE)
        ->execute();
      
      $value = [];
      
      foreach ($ids as $id) {
        $entity = \Drupal::entityTypeManager()->getStorage($type)->load($id);
        $value = $entity->get($field)->getValue();
        $val_str = implode("|",array_column($value, 'target_id'));
        
        if (str_contains($val_str, $target)) {
          echo "Skipping $type [$id] because it already contains merge target $field [$target]\n";
        }
        else {
          $value[] = ['target_id' => strval($target)];
          $entity->set($field, $term);
          echo "Adding target $field [$target] to $type [$id] with mergeable $field [$merged]\n";
          // $entity->save();
        }
      }  
    }
  }
}
