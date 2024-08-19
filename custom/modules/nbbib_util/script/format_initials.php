<?php

/**
 * @file
 * Contains format_initials.php.
 */

format_initials('yabrm_contributor');

function format_initials($type) {
  $handler = \Drupal::entityTypeManager()->getStorage($type);
  $entities = $handler->loadMultiple(\Drupal::entityQuery($type)->accessCheck(FALSE)->execute());
  foreach ($entities as $entity) {
    $first_name = trim($entity->getFirstName());
    $last_name = trim($entity->getLastName());
    $institution_name = $entity->getInstitutionName() ? trim($entity->getInstitutionName()) : NULL;

    if (!$institution_name) {
      $lastchar = substr($first_name, -1);
      $prevchar = substr($first_name, -2, 1);

      if (ctype_alpha($lastchar) && ($prevchar == ' ' or $prevchar == '' )) {
        echo "\nUpdating contributor [$first_name $last_name] -> [$first_name. $last_name]";
        $entity->setFirstName("$first_name.");
        $entity->save();
      }
    }
  }  
  echo "\nAll entities of type [$type] updated.\n";
}