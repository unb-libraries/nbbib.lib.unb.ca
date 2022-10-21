<?php

/**
 * @file
 * Contains rm_paragraph.php.
 */

rm_entities('paragraph');

/**
 * {@inheritdoc}
 */
function rm_entities($type) {
  $handler = \Drupal::entityTypeManager()->getStorage($type);
  $entities = $handler->loadMultiple(\Drupal::entityQuery($type)->execute());
  $handler->delete($entities);
}
