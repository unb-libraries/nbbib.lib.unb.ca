<?php

use Scriptotek\Marc\Collection;
use Scriptotek\Marc\Record;

$source = 'modules/custom/unblib_marc/data/portolan.mrc';
$collection = Collection::fromFile($source);

foreach ($collection as $record) {
  echo getMarcValue($record, '245', 'a') . "\n";
}

function getMarcValue(Record $record, string $field, string $subfield) {
  if ($field_data = $record->getField($field)) {
    return $field_data->getSubfield($subfield)->getData();
  }

  return NULL;
}