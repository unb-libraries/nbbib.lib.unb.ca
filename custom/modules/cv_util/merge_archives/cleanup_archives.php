<?php

/**
 * @file
 * Contains merge_archives.php
 */

 use Drupal\yabrm\Entity\BookReference;
 use Drupal\yabrm\Entity\BookSectionReference;
 use Drupal\yabrm\Entity\JournalArticleReference;
 use Drupal\yabrm\Entity\ThesisReference;

$merges = [
  [
    'target' => 1352,
    'merged' => [
      736,
    ],
  ],
  [
    'target' => 1314,
    'merged' => [
      761,
      765,
    ],
  ],
  [
    'target' => 862,
    'merged' => [
      57,
    ],
  ],
  [
    'target' => 824,
    'merged' => [
      1332,
      1348,
    ],
  ],
  [
    'target' => 784,
    'merged' => [
      1326,
      825,
    ],
  ],
  [
    'target' => 779,
    'merged' => [
      1327,
    ],
  ],
  [
    'target' => 759,
    'merged' => [
      748,
      1333,
      1347,
    ],
  ],
  [
    'target' => 733,
    'merged' => [
      23,
    ],
  ],
  [
    'target' => 708,
    'merged' => [
      478,
      1331,
    ],
  ],
  [
    'target' => 701,
    'merged' => [
      711,
      846,
    ],
  ],
  [
    'target' => 696,
    'merged' => [
      695,
    ],
  ],
  [
    'target' => 672,
    'merged' => [
      39,
      481,
    ],
  ],
  [
    'target' => 632,
    'merged' => [
      1344,
    ],
  ],
  [
    'target' => 603,
    'merged' => [
      553,
      568,
      1328,
    ],
  ],
  [
    'target' => 586,
    'merged' => [
      402,
      1339,
    ],
  ],
  [
    'target' => 569,
    'merged' => [
      592,
      1315,
    ],
  ],
  [
    'target' => 562,
    'merged' => [
      1453,
    ],
  ],
  [
    'target' => 557,
    'merged' => [
      22,
      683,
    ],
  ],
  [
    'target' => 537,
    'merged' => [
      33,
      243,
      912,
      925,
    ],
  ],
  [
    'target' => 536,
    'merged' => [
      29,
      38,
      41,
      44,
      47,
      244,
      387,
      403,
      463,
      464,
      468,
      469,
      470,
      472,
      476,
      480,
      497,
      522,
      625,
      681,
      817,
      1376,
      1518,
      1628,
      1629,
      138,
      249,
      273,
      1338,
    ],
  ],
  [
    'target' => 428,
    'merged' => [
      171,
      180,
      197,
      238,
      559,
      1324,
      1345,
    ],
  ],
  [
    'target' => 390,
    'merged' => [
      713,
    ],
  ],
  [
    'target' => 241,
    'merged' => [
      796,
    ],
  ],
  [
    'target' => 31,
    'merged' => [
      193,
      198,
    ],
  ],
  [
    'target' => 20,
    'merged' => [
      34,
    ],
  ],
];

$merged = array_values(array_column($merges, 'merged'));
$ids = [];
foreach ($merged as $merge) {
  $ids = array_merge($ids, $merge);
}

$handler = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
$entities = $handler->loadMultiple(
  \Drupal::entityQuery('taxonomy_term')
    ->condition('vid', 'nbbib_archives')
    ->condition('tid', $ids, 'IN')
    ->accessCheck(FALSE)
    ->execute()
);
foreach ($entities as $entity) {
  $id = $entity->id();
  echo "Deleting merged archive [$id]";
}
$handler->delete($entities);
drupal_flush_all_caches();
