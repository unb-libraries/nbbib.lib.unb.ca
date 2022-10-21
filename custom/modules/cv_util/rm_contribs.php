<?php

/**
 * @file
 * Contains rm_contribs.php.
 */

rm_entities('yabrm_contributor');

/**
 * {@inheritdoc}
 */
function rm_entities($type) {
  $handler = \Drupal::entityTypeManager()->getStorage($type);
  $entities = $handler->loadMultiple(\Drupal::entityQuery($type)->execute());
  $handler->delete($entities);
}
