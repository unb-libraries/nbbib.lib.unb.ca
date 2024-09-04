<?php

$arg1 = isset($extra[0]) ? $extra[0] : NULL;
$timestamp = strtotime($arg1);

if ($timestamp) {
  rm_entities('yabrm_book', $timestamp);
  rm_entities('yabrm_book_section', $timestamp);
  rm_entities('yabrm_journal_article', $timestamp);
  rm_entities('yabrm_thesis', $timestamp);
  rm_entities('yabrm_collection', $timestamp);
  rm_entities('yabrm_contributor', $timestamp);
  rm_entities('paragraph', $timestamp);
  rm_terms($timestamp);
}

function rm_entities($type, $timestamp) {
  $readable = date(DATE_ATOM, $timestamp);
  $handler = \Drupal::entityTypeManager()->getStorage($type);
  $entities = $handler->loadMultiple(\Drupal::entityQuery($type)
    ->accessCheck(FALSE)
    ->condition('created', $timestamp, '>')
    ->execute());

  $handler->delete($entities);
  echo "All entities of type [$type] created after [$readable] removed.\n";
}

function rm_terms($timestamp) {
  $readable = date(DATE_ATOM, $timestamp);
  $handler = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $entities = $handler->loadMultiple(\Drupal::entityQuery('taxonomy_term')
    ->accessCheck(FALSE)
    ->condition('changed', $timestamp, '>')
    ->execute());

  $handler->delete($entities);
  echo "All taxonomy terms created after [$readable] removed.\n";
}
