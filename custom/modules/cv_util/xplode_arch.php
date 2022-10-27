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

$to_delete = [];

// Iterate through types and process.
foreach ($bundles as $bundle) {
  $new_to_delete = xplode_arch($bundle);
  echo "\nAll entities of type [$bundle] processed for archives.\n";
  $to_delete = array_merge($to_delete, $new_to_delete);
}

// Delete old, imploded archive terms.
if (!empty($to_delete)) {
  $term_storage = \Drupal::entityTypeManager()
    ->getStorage('taxonomy_term');
  $entities = $term_storage->loadMultiple($to_delete);
  $term_storage->delete($entities);
}

echo "\nThe following terms have been removed:\n";
echo print_r($to_delete);
echo "\n";

/**
 * Iterates through entities and processes comma-exploded archive values.
 */
function xplode_arch($type) {
  $handler = \Drupal::entityTypeManager()->getStorage($type);
  $entities = $handler->loadMultiple(\Drupal::entityQuery($type)->execute());
  $terms_processed = [];

  // Process all 4 types of yabrm reference.
  foreach ($entities as $entity) {
    // Get archive ids.
    $arch_ids = array_column($entity->archive->getValue(), 'target_id') ?? NULL;

    // If any archive ids...
    if ($arch_ids) {
      // Process only first archive entry (migrated values, comma sepparated).
      $arch_id = $arch_ids[0];

      // Load term and get name.
      $term = Term::load($arch_id);
      $name = $term->getName();

      // If the name contains commas and not 'CIHM' (known to contain commas)...
      if (str_contains($name, ',') and !str_contains($name, 'CIHM')) {
        // Explode archive names on comma.
        $names = explode(',', $name);
        $archives = [];

        // For each exploded name...
        foreach ($names as $arch_name) {
          // Check if it exists.
          $existing = tax_term_exists($arch_name, 'name', 'nbbib_archives');

          // If it doesn't exist, create it.
          if (!$existing) {
            $archive = Term::create([
              'name' => $arch_name,
              'vid' => 'nbbib_archives',
            ]);

            $archive->save();
          }
          // If it exists, load it.
          else {
            $archive = Term::load($existing);
          }

          // Add entry to reference multivalue archives field array.
          $archives[] = ['target_id' => $archive->id()];
        }

        echo "\nProcessing reference: ";
        echo $entity->id();
        echo "\nReplacing archive reference:\n";
        echo print_r($entity->archive->getValue());
        echo "\n";
        // Update archives in yabrm reference, save.
        $entity->archive->setValue($archives);
        $entity->save();
        // List original term for deletion.
        $terms_processed[] = $term->id();
        echo "With:\n";
        echo print_r($entity->archive->getValue());
        echo "\n";
      }
    }
  }

  return $terms_processed;
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
