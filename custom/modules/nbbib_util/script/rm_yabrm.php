<?php

rm_entities('yabrm_book');
rm_entities('yabrm_book_section');
rm_entities('yabrm_journal_article');
rm_entities('yabrm_thesis');
rm_entities('yabrm_collection');
rm_entities('paragraph');
rm_entities('yabrm_contributor');
rm_entities('taxonomy_term');

function rm_entities($type) {
  $handler = \Drupal::entityTypeManager()->getStorage($type);
  $entities = $handler->loadMultiple(\Drupal::entityQuery($type)->accessCheck(FALSE)->execute());
  $handler->delete($entities);
  echo "All entities of type [$type] removed.\n";
}
