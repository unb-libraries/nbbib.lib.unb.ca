<?php

$arg1 = isset($extra[0]) ? $extra[0] : NULL;
$arg2 = isset($extra[1]) and ($extra[1] == 'y') ? TRUE : FALSE;
$timestamp = strtotime($arg1);

if ($timestamp) {
  pub_entities('yabrm_book', $timestamp);
  pub_entities('yabrm_book_section', $timestamp);
  pub_entities('yabrm_journal_article', $timestamp);
  pub_entities('yabrm_thesis', $timestamp);
  pub_entities('yabrm_collection', $timestamp);
  pub_entities('yabrm_contributor', $timestamp);
  pub_entities('paragraph', $timestamp);
}

function pub_entities($type, $timestamp) {
  $readable = date(DATE_ATOM, $timestamp);
  $handler = \Drupal::entityTypeManager()->getStorage($type);
  $entities = $handler->loadMultiple(\Drupal::entityQuery($type)
    ->accessCheck(FALSE)
    ->condition('created', $timestamp, '>')
    ->execute());

  foreach ($entities as $entity) {
    $entity->setPublished($arg2)->save();
  }

  $update = $arg2 ? 'published' : 'unpublished';

  echo "All entities of type [$type] modified after [$readable] $update.\n";
}

