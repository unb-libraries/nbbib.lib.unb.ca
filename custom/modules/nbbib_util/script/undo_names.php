<?php

upd_entities('yabrm_book');
upd_entities('yabrm_book_section');
upd_entities('yabrm_journal_article');
upd_entities('yabrm_thesis');

function upd_entities($type) {
  $handler = \Drupal::entityTypeManager()->getStorage($type);
  $entities = $handler->loadMultiple(\Drupal::entityQuery($type)->accessCheck(FALSE)->execute());
  foreach ($entities as $entity) {
    $first_name = trim($entity->getFirstName());
    $last_name = trim($entity->getLastName());
    $institution_name = $entity->getInstitutionName() ? trim($entity->getInstitutionName()) : NULL;
    if ($institution_name) {
        $entity->setName("$institution_name");
    }
    else {
        $entity->setName("$first_name $last_name");
    }
  }  
  echo "All entities of type [$type] updated.\n";
}