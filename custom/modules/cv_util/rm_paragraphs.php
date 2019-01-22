<?php

/**
 * @file
 * Contains rm-contribs.php.
 */

rm_entities('paragraph');

/**
 * {@inheritdoc}
 */
function rm_entities($type) {
  entity_delete_multiple($type, \Drupal::entityQuery($type)->execute());
}
