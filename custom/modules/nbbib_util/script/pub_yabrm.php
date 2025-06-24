<?php

$arg1 = isset($extra[0]) ? $extra[0] : NULL;
$arg2 = isset($extra[1]) and ($extra[1] == 'y') ? TRUE : FALSE;
$timestamp = strtotime($arg1);

if ($timestamp) {
  pub_entities('yabrm_book', $timestamp, $arg2);
  pub_entities('yabrm_book_section', $timestamp, $arg2);
  pub_entities('yabrm_journal_article', $timestamp, $arg2);
  pub_entities('yabrm_thesis', $timestamp, $arg2);
  pub_entities('yabrm_conference', $timestamp, $arg2);
  pub_entities('yabrm_report', $timestamp, $arg2);
  pub_entities('yabrm_film', $timestamp, $arg2);
  pub_entities('yabrm_website', $timestamp, $arg2);
  pub_entities('yabrm_collection', $timestamp, $arg2);
  pub_entities('yabrm_contributor', $timestamp, $arg2);
  pub_entities('paragraph', $timestamp, $arg2);
}

function pub_entities($type, $timestamp, $publish) {
  $readable = date(DATE_ATOM, $timestamp);
  $handler = \Drupal::entityTypeManager()->getStorage($type);
  $entities = $handler->loadMultiple(\Drupal::entityQuery($type)
    ->accessCheck(FALSE)
    ->condition('created', $timestamp, '>')
    ->execute());

  $update = $publish ? 'published' : 'unpublished';

  foreach ($entities as $entity) {
    $entity->setPublished($publish)->save();
  }

  echo "All entities of type [$type] modified after [$readable] $update.\n";
}

