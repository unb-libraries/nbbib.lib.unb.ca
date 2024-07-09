<?php

use Scriptotek\Marc\Collection;
use Scriptotek\Marc\Record;

$source = 'modules/custom/unblib_marc/data/portolan.mrc';
$collection = Collection::fromFile($source);

$map = [
  'item_type' => [
    'value' => 'book',
  ],
  'external_key_ref' => [
    'marc' => '001',
  ],
  'title' => [
    'marc' => '245$a',
  ],
];

foreach ($collection as $record) {
  echo getMarcValue($record, '100', 'a', TRUE) . "\n";
}

function getMarcValue(Record $record, string $field, string $subfield, bool $cleanup = FALSE) {
  if ($field_data = $record->getField($field)) { 

    if ($subfield) {
      $data = $field_data->getSubfield($subfield)->getData();
    }
    else {
      $data = $field_data->toRaw();
    }

    $data = (is_string($data) and $cleanup) ? preg_replace('(^[^\p{L}\p{N}]+|[^\p{L}\p{N}]+$)u', '', $data) : $data;
    $data = (substr($data, -2, 1) == ' ') ? "$data." : $data;

    return $data;
  }
  
  return NULL;
}

$arg1 = $extra[0];

echo "\n$arg1\n";