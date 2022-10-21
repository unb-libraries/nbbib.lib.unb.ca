<?php

/**
 * @file
 * Contains xplode_arch.php.
 *
 * Explodes and processes comma-sepparated archive(s) imported from Zotero.
 */

use Drupal\taxonomy\Entity\Term;

// Entity types to be processed.
$bundles = [
  'yabrm_book',
  'yabrm_thesis',
  'yabrm_book_section',
  'yabrm_journal_article',
];

// Iterate through types and process.
foreach ($bundles as $bundle) {
  xplode_arch($bundle);
}

/**
 * Iterates through entities and processes comma-exploded archive values.
 */
function xplode_arch($type) {
  $handler = \Drupal::entityTypeManager()->getStorage($type);
  $entities = $handler->loadMultiple(\Drupal::entityQuery($type)->execute());

  foreach ($entities as $entity) {
    $arch_ids = array_column($entity->archive->getValue(), 'target_id') ?? NULL;

    if ($arch_ids) {
      $arch_id = $arch_ids[0];

      $term = Term::load($arch_id);
      $name = $term->getName();

      if (str_contains($name, ',') and !str_contains($name, 'CIHM')) {
        $names = explode(',', $name);
        $archives = [];

        foreach ($names as $arch_name) {
          $existing = tax_term_exists($arch_name, 'name', 'nbbib_archives');

          if (!$existing) {
            $archive = Term::create([
              'name' => $arch_name,
              'vid' => 'nbbib_archives',
            ]);

            $archive->save();
          }
          else {
            $archive = Term::load($existing);
          }
          $archives[] = ['target_id' => $archive->id()];
        }

        $entity->archive->setValue($archives);
        $entity->save();
        $term->delete();

        echo print_r($name);
        echo print_r($entity->archive->getValue());
      }
    }
  }

  echo "\nAll entities of type [$type] removed.\n";
}

/**
 * Check if a taxonomy term exists.
 *
 * @param string $value
 *   The name of the term.
 * @param string $field
 *   The field to match when validating.
 * @param string $vocabulary
 *   The vid to match.
 *
 * @return mixed
 *   Contains an INT of the tid if exists, FALSE otherwise.
 */
function tax_term_exists($value, $field, $vocabulary) {
  $tids = \Drupal::entityQuery('taxonomy_term')
    ->condition('vid', $vocabulary)
    ->condition($field, $value)
    ->execute();

  if (!empty($tids)) {
    foreach ($tids as $tid) {
      return $tid;
    }
  }
  return FALSE;
}
