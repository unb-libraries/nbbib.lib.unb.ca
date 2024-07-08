<?php

use Scriptotek\Marc\Collection;

$source = 'modules/custom/unblib_marc/data/portolan.mrc';
$collection = Collection::fromFile($source);

foreach ($collection as $record) {
  echo $record->title . "\n";
}