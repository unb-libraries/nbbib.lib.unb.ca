<?php

$arg1 = (isset($extra[0]) and $extra[0] == 'safe') ? FALSE : TRUE;

extra_oclc('yabrm_book', $arg1);
extra_oclc('yabrm_book_section', $arg1);
extra_oclc('yabrm_journal_article', $arg1);
extra_oclc('yabrm_thesis', $arg1);
extra_oclc('yabrm_conference', $arg1);
extra_oclc('yabrm_report', $arg1);
extra_oclc('yabrm_film', $arg1);

function extra_oclc($type, $update) {
  $handler = \Drupal::entityTypeManager()->getStorage($type);
  $entities = $handler->loadMultiple(\Drupal::entityQuery($type)
    ->accessCheck(FALSE)
    ->execute());

  foreach ($entities as $entity) {
    $id = $entity->id();
    $extraval = $entity->extra ? $entity->extra->getValue()[0]['value'] : NULL;
    $oclc = str_contains(strtolower($extraval), 'oclc') ?? preg_replace("/[^0-9]/", '', $extraval);

    if ($oclc != '') {
      echo "Retrieved OCLC number [$oclc] from Extra field value [$extraval] in $type [$id].\n";
    }
    else {
      $update = FALSE;
    }
    
    if ($update) {
      $entity->extra = $oclc;
      $entity->save();
      echo "Updated OCLC field to value [$oclc] in $type [$id].\n";
    }
  }

  echo "All entities of type [$type] processed.\n\n";
}

