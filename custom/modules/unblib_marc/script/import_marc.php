<?php

use Drupal\yabrm\Entity\BookReference;
use Scriptotek\Marc\Collection;
use Scriptotek\Marc\Record;

$source = 'modules/custom/unblib_marc/data/portolan.mrc';
$collection = Collection::fromFile($source);

$map = [
  'external_key_ref' => [
    'marc' => '001',
    'marc_fallback' => '020$a',
    'default' => 'NO_EXTERNAL_KEY'
  ],
  'title' => [
    'marc' => '245$a',
  ],
  'abstract_note' => [
    'marc' => '520$a',
  ],
  'publication_year' => [
    'marc' => '260$c',
    'process' => 'date2dmy',
  ],
  'contributors' => [
    'marc' => '700$a',
    'multivalue' => TRUE,
    'process' => 'create_contribs',
  ],
  'short_title' => [
    'marc' => '246$a',
  ],
  'language' => [
    'marc' => '041$a',
    'process' => 'marc2lang',
  ],
  'rights' => [
    'marc' => '540$a',
  ],
  'archive' => [
    'marc' => '850$a',
  ],
  'archive_location' => [
    'marc' => '852$a',
  ],
  'library_catalog' => [
    'marc' => '040$a',
  ],  
  'call_number' => [
    'marc' => '050$a',
  ],
  'notes' => [
    'marc' => '700',
  ],
  'isbn' => [
    'marc' => '020$a',
  ],
  'volume' => [
    'marc' => '490$v',
  ],
  'series' => [
    'marc' => '490$a',
  ],
  'publisher' => [
    'marc' => '260$b',
  ],
  'place' => [
    'marc' => '260$a',
  ],
  'edition' => [
    'marc' => '250$a',
  ],
  'physical_description' => [
    'marc' => '300',
  ],
];

migrateMarc(
  'modules/custom/unblib_marc/data/portolan.mrc',
  'yabrm_book',
  $map
);

function migrateMarc(string $source, string $entity_type, array $map) {
  $collection = Collection::fromFile($source);
  $n = 0;

  foreach ($collection as $record) {
    $entity = \Drupal::entityTypeManager()->getStorage($entity_type)->create();
    
    foreach ($map as $field => $mapping) {
      if (isset($mapping['marc'])) {
        $params = explode('$', $mapping['marc']);
      }
      elseif (isset($mapping['marc_fallback'])) {
        $params = explode('$', $mapping['marc_fallback']);
      }
      $marc_field = $params[0] ?? NULL; 
      $marc_subfield = $params[1] ?? '';
      $multival = isset($mapping['multival']) and $mapping['multival'];
        
      if ($marc_field) {
        $value = getMarcValue($record, $marc_field, $marc_subfield, TRUE, $multival);
      }
      elseif (isset($mapping['default'])) {
        $value = $mapping['default'];
      }
      elseif (isset($mapping['value'])) {
        $value = $mapping['value'];
      }

      if(isset($mapping['process'])) {
        $callback = $mapping['process'];
        if (is_callable($callback)) {
          if (function_exists($callback)) {
            $value = $callback($field, $value);
          }
        }
      }
      
      if ($value) {
        $entity->set($field, $value);
      }
    }
    $n++;
    
    if ($n == 100) {
      exit;
    }
    

    // @TODO: Pass array of mandatory fields and only save if constraints met.
    $entity->save(); 
  }
}

function getMarcValue(
  Record $record, 
  string $field, 
  string $subfield, 
  bool $cleanup = FALSE,
  bool $multival = FALSE
  ) {
  // If there is field data...
  $field_data = $multival ? $record->query($field) : $record->getField($field);
  if ($field_data) {
    // If a subfield is requested and valid...
    if ($subfield and gettype($field_data->getSubfield($subfield)) == 'object') {
      // Get data.
      $data = $field_data->getSubfield($subfield)->getData();
    }
    else {
      // Get raw broad field data.
      $data = $multival ? $data : $field_data->toRaw();
    }
    // If cleanup is requested, trim spaces and special characters.
    $data = (is_string($data) and $cleanup) ? 
      preg_replace('(^[^\p{L}\p{N}]+|[^\p{L}\p{N}]+$)u', '', $data) : $data;
    // Restore trailing period if last character appears to be an initial.
    $data = (substr($data, -2, 1) == ' ') ? "$data." : $data;

    return $data;
  }
  
  return NULL;
}

function date2dmy($field, $date) {
  preg_match('~\b\d{4}\b\+?~', $date, $year);

  if (isset($year[0])) {
    return $year[0];
  }
  
  return;
}

function create_contribs($field, $contribs) {
} 

$arg1 = $extra[0];

echo "\n$arg1\n";