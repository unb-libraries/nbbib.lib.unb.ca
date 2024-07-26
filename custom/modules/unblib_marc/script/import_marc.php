<?php

use Drupal\yabrm\Entity\BookReference;
use Scriptotek\Marc\Collection;
use Scriptotek\Marc\Fields\Field;
use Scriptotek\Marc\Record;

$source = 'modules/custom/unblib_marc/data/portolan.mrc';
$collection = Collection::fromFile($source);

$map = [
  'external_key_ref' => [
    'marc' => '001',
    'marc_fallback' => '020$a',
    'default' => 'NO_EXTERNAL_KEY',
    'cleanup' => TRUE,
  ],
  'title' => [
    'marc' => '245',
    'process' => 'full_title'
  ],
  'abstract_note' => [
    'marc' => '520$a',
    'cleanup' => TRUE,
  ],
  'publication_year' => [
    'marc' => '260$c',
    'process' => 'date2dmy',
    'cleanup' => TRUE,
  ],
  'author' => [
    'marc' => '100$a',
    'process' => 'create_contribs',
    'cleanup' => TRUE,
  ],
  'contributors' => [
    'marc' => '700',
    'multival' => TRUE,
    'process' => 'create_contribs',
  ],
  'short_title' => [
    'marc' => '246$a',
    'cleanup' => TRUE,
  ],
  'language' => [
    'marc' => '041$a',
    'process' => 'marc2lang',
    'cleanup' => TRUE,
  ],
  'rights' => [
    'marc' => '540$a',
    'cleanup' => TRUE,
  ],
  'archive' => [
    'marc' => '850$a',
    'cleanup' => TRUE,
  ],
  'archive_location' => [
    'marc' => '852$a',
    'cleanup' => TRUE,
  ],
  'library_catalog' => [
    'marc' => '040$a',
    'cleanup' => TRUE,
  ],  
  'call_number' => [
    'marc' => '050$a',
    'cleanup' => TRUE,
  ],
  'notes' => [
    'marc' => '700',
    'cleanup' => TRUE,
  ],
  'isbn' => [
    'marc' => '020$a',
    'cleanup' => TRUE,
  ],
  'volume' => [
    'marc' => '490$v',
    'cleanup' => TRUE,
  ],
  'series' => [
    'marc' => '490$a',
    'cleanup' => TRUE,
  ],
  'publisher' => [
    'marc' => '260$b',
    'cleanup' => TRUE,
  ],
  'place' => [
    'marc' => '260$a',
    'cleanup' => TRUE,
  ],
  'edition' => [
    'marc' => '250$a',
    'cleanup' => TRUE,
  ],
  'physical_description' => [
    'marc' => '300',
    'cleanup' => TRUE,
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
      $cleanup = isset($mapping['cleanup']) and $mapping['cleanup'];
      $multival = isset($mapping['multival']) and $mapping['multival'];
        
      if ($marc_field) {
        $value = getMarcValue($record, $marc_field, $marc_subfield, $cleanup, $multival);
        echo "\n|FIELD|$field: $value";
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
        //$entity->set($field, $value);
      }
    }
    $n++;
    echo "\n**********";
    
    if ($n == 100) {
      exit;
    }
    
    // @TODO: Pass array of mandatory fields and only save if constraints met.
    $entity->save(); 
  }
}

function getMarcValue(
  Record $record, 
  string $marc_field, 
  string $marc_subfield, 
  bool $cleanup = FALSE,
  bool $multival = FALSE
  ) {
  // If multivalue, run query. If single value, get field.
  $field_data = $multival ? $record->query($marc_field) : [$record->getField($marc_field)];
  $data = "";
  $entries = 0;

  foreach ($field_data as $entry) {
    if ($entry) {
      if ($marc_subfield and gettype($entry->getSubfield($marc_subfield)) == 'object') {
        // Get entry data.
        $entry_data = $entry->getSubfield($marc_subfield)->getData();
      }
      else {
        // Get raw, broad field data.
        $entry_data = $entry->toRaw();
      }

      // If cleanup is requested, trim spaces and special characters.
      $entry_data = (is_string($entry_data) and $cleanup) ? 
      preg_replace('(^[^\p{L}\p{N}]+|[^\p{L}\p{N}]+$)u', '', $entry_data) : $entry_data;
      // Restore trailing period if last character appears to be an initial.
      $entry_data = (substr($entry_data, -2, 1) == ' ') ? "$entry_data." : $entry_data;
      // Concatenate data string.
      $data .= $entries > 0 ? "|$entry_data" : $entry_data;
      $entries++;
    }
  }

  return $data;
}


function date2dmy($field, $date) {
  preg_match('~\b\d{4}\b\+?~', $date, $year);

  if (isset($year[0])) {
    return $year[0];
  }
  
  return;
}

function create_contribs($field, $contribs) {
  return FALSE;
}

function full_title($field, $title) {
  return $title;
}

$arg1 = $extra[0];

echo "\n$arg1\n";