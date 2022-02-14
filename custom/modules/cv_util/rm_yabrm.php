<?php

/**
 * @file
 * Contains rm-contribs.php.
 */

rm_entities('yabrm_paragraph');
rm_entities('yabrm_book');
rm_entities('yabrm_book_section');
rm_entities('yabrm_journal_article');
rm_entities('yabrm_thesis');
rm_entities('yabrm_contributor');

/**
 * {@inheritdoc}
 */
function rm_entities($type) {
  $handler = \Drupal::entityTypeManager()->getStorage($type);
  $entities = $handler->loadMultiple(\Drupal::entityQuery($type)->execute());
  $handler->delete($entities);
  echo "All entities of type [$type] removed.\n";
}
