<?php

/**
 * @file
 * Contains rm_orphan_contribs.php.
 */

$query = \Drupal::database()->query(
  "SELECT id
  FROM paragraphs_item
  WHERE type = 'yabrm_bibliographic_contributor'
  AND id NOT IN (SELECT contributors_target_id FROM yabrm_book__contributors)
  AND id NOT IN (SELECT contributors_target_id FROM yabrm_book_section__contributors)
  AND id NOT IN (SELECT contributors_target_id FROM yabrm_journal_article__contributors)
  AND id NOT IN (SELECT contributors_target_id FROM yabrm_thesis__contributors)"
);

$ids = array_column($query->fetchAll(), 'id');

if (count($ids) > 0) {
  $handler = \Drupal::entityTypeManager()->getStorage('paragraph');
  $entities = $handler->loadMultiple($ids);
  echo "\nDeleting the following orphan contributor paragraphs:\n";
  var_dump($ids);
  $handler->delete($entities);
}
else {
  echo "\nNo orphan contributor paragraphs found.\n";
}
