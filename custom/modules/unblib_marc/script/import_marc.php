<?php

use Scriptotek\Marc\Collection;
use Scriptotek\Marc\Record;

$source = 'modules/custom/unblib_marc/data/portolan.mrc';
$collection = Collection::fromFile($source);

foreach ($collection as $record) {
  echo getMarcValue($record, '245', 'a', TRUE) . "\n";
}

function getMarcValue(Record $record, string $field, string $subfield, bool $clean = FALSE) {
  if ($field_data = $record->getField($field)) {
    $data = $field_data->getSubfield($subfield)->getData();
    $data = (is_string($data) and $clean) ? preg_replace('(^[^\p{L}\p{N}]+|[^\p{L}\p{N}]+$)u', '', $data) : $data;
    
    return $data;
  }

  return NULL;
}