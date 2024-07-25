<?php

upd_entities('yabrm_contributor');

function upd_entities($type) {
  $handler = \Drupal::entityTypeManager()->getStorage($type);
  $entities = $handler->loadMultiple(\Drupal::entityQuery($type)->accessCheck(FALSE)->execute());
  foreach ($entities as $entity) {
    $first_name = trim($entity->getFirstName());
    $last_name = trim($entity->getLastName());
    $institution_name = $entity->getInstitutionName() ? trim($entity->getInstitutionName()) : NULL;
    if (!$institution_name) {
      $entity->setName("$first_name $last_name");
      $entity->save();
      echo "\nUpdated contributor [$first_name $last_name]";
    }
  }  
  echo "\nAll entities of type [$type] updated.\n";
}