<?php

pub_entities('yabrm_contributor');
pub_entities('yabrm_book');
pub_entities('yabrm_book_section');
pub_entities('yabrm_journal_article');
pub_entities('yabrm_thesis');

function pub_entities($type) {
  $handler = \Drupal::entityTypeManager()->getStorage($type);
  $entities = $handler->loadMultiple(\Drupal::entityQuery($type)->accessCheck(FALSE)->execute());
  foreach ($entities as $entity) {
    $entity->setPublished(TRUE)->save();
  }  
  echo "All entities of type [$type] published.\n";
}
