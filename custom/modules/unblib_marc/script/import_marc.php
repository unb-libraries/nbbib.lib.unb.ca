<?php

use Drupal\paragraphs\Entity\Paragraph;
use Drupal\taxonomy\Entity\Term;
use Drupal\yabrm\Entity\BibliographicContributor;
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
    'marc' => '245$a',
    'cleanup' => TRUE,
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
    'target' => 'contributors',
    'process' => 'create_author',
    'cleanup' => TRUE,
  ],
  'contributors' => [
    'marc' => '700$a$e',
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
    'marc' => '700$a$d$e$l',
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

/** */
migrateMarc(
  'modules/custom/unblib_marc/data/portolan.mrc',
  'yabrm_book',
  $map,
  FALSE
);
/** */

function migrateMarc(string $source, string $entity_type, array $map, bool $publish) {
  $collection = Collection::fromFile($source);
  $n = 0; // Debug.

  foreach ($collection as $record) {
    $entity = \Drupal::entityTypeManager()->getStorage($entity_type)->create();
    
    foreach ($map as $field => $mapping) {
      $field = $mapping['target'] ?? $mapping['target'] ?? $field;
      $marc = $mapping['marc'] ?? $mapping['marc_fallback'] ?? NULL;
      $cleanup = isset($mapping['cleanup']) and $mapping['cleanup'];
      $multival = isset($mapping['multival']) and $mapping['multival'];

      if ($marc) {
        $value = getMarcValue($record, $marc, $cleanup, $multival);
      }
      elseif ($mapping['default']) {
        $value = $mapping['default']; 
      }

      echo "\n|FIELD|$field: $value";

      if ($value) {
        if(isset($mapping['process'])) {
          $callback = $mapping['process'];
          if (is_callable($callback)) {
            if (function_exists($callback)) {
              $value = $callback($value, $entity);
            }
          }
        }
      
        $entity->set($field, $value);
      }
    }
    $n++; // Debug.
    echo "\n**********";
    
    if ($n == 100) { // Debug.
      exit;
    }
    
    // @TODO: Pass array of mandatory fields and only save if constraints met.
    if ($entity->getTitle()) {
      $entity->setPublished($publish);
      $entity->save();
    } 
  }
}

function getMarcValue(
  Record $record, 
  string $marc, 
  bool $cleanup = FALSE,
  bool $multival = FALSE
  ) {
  // If multivalue, run query. If single value, get field.
  $field_data = $record->query($marc);
  $data = "";
  $entries = 0;

  foreach ($field_data as $entry) {
    if ($entry) {
      $entry_data = $entry->toRaw();
    }

    // If cleanup is requested, trim spaces and special characters.
    $entry_data = (is_string($entry_data) and $cleanup) ? 
    preg_replace('(^[^\p{L}\p{N}]+|[^\p{L}\p{N}]+$)u', '', $entry_data) : $entry_data;
    // Restore trailing period if last character appears to be an initial.
    $entry_data = (is_string($entry_data) and  substr($entry_data, -2, 1) == ' ') ? "$entry_data." : $entry_data;
    // Concatenate data string.
    $data .= "||$entry_data";
    $entries++;
  }

  $data = $data ?? "$data||";
  return $data;
}

function date2dmy($date, &$entity) {
  preg_match('~\b\d{4}\b\+?~', $date, $year);

  if (isset($year[0])) {
    return $year[0];
  }
  
  return;
}

function create_author($author_name, &$entity) {
  $author = parseSub('a', $author_name);
  return createContributors([$author], 'Author');
}

function create_contribs($contrib_blob, &$entity) {
  echo "\nCONTRIB BLOB: $contrib_blob";
  return;
}

function parseSub($subfield, $data) {
  $pattern = "/\|\|$subfield(.*?)\|\|/";
  if (preg_match($pattern, $data, $matches)) {
    return $matches[1];
  }
  else {
    return 'ERR';
  }
}

/**
 * Create contributor paragraph entities.
 *
 * @param array $contrib_names
 *   Array containing the names of contributors to add.
 * @param string $contrib_role
 *   The contributor role to apply.
 *
 * @return Drupal\paragraphs\Entity\Paragraph[]
 *   The created paragraph entities
 *
 * @throws Drupal\Core\Entity\EntityStorageException
 */
function createContributors($contrib_names, $contrib_role) {
  // Contributors.
  $contrib_ids = [];

  // Create contribs.
  foreach ($contrib_names as $contrib_name) {
    // Trim whitespace, tabs, etc.
    $contrib_name = trim($contrib_name, " \t\n\r\0\x0B");
    // Keep exact trimmed copy to prevent duplicate import.

    // If contributor is anonymous...
    if (strpos(mb_strtolower($contrib_name), 'anonymous')) {
      $contrib_name = 'Anonymous';
    }

    $zotero_name = $contrib_name;

    if (!empty($contrib_name)) {
      $existing = \Drupal::entityTypeManager()->getStorage('yabrm_contributor')
        ->getQuery()
        ->condition('zotero_name', $zotero_name)
        ->accessCheck(FALSE)
        ->execute();

      reset($existing);
      $contrib_id = key($existing);

      // Create contributor if doesn't exist.
      if (empty($contrib_id)) {
        $institution_name = NULL;
        $first_name = NULL;
        $last_name = NULL;
        $sort_name = NULL;

        $split_name = explode(',', $contrib_name);

        if ($split_name[0] == $contrib_name) {
          $institution_name = $contrib_name;
          $sort_name = $institution_name;
        }
        else {
          $last_name = trim($split_name[0]);
          $first_name = trim(implode(',', array_slice($split_name, 1)));
          $sort_name = $last_name;
        }

        $contrib = BibliographicContributor::create([
          'zotero_name' => $zotero_name,
          'institution_name' => $institution_name,
          'first_name' => $first_name,
          'last_name' => $last_name,
          'sort_name' => $sort_name,
          'status' => FALSE,
        ]);

        $contrib->save();
        $contrib_id = $contrib->id();
      }

      // Populate array with contributor ids.
      $contrib_ids[] = $contrib_id;
    }
  }

  // Create contrib Paragraph references using ids.
  $contributors = [];
  $voc = 'yabrm_contributor_roles';
  $field = 'name';

  foreach ($contrib_ids as $contrib_id) {
    // Retrieve contributor role ID, or create term.
    $role_term = ucwords(str_replace('_', ' ', $contrib_role));
    $role_tid = taxTermExists($role_term, $field, $voc);

    if (!$role_tid) {
      $term = Term::create([
        'vid' => $voc,
        $field => $role_term,
      ]);
      $term->save();

      $role_tid = $term->id();
    }

    // Create Paragraph.
    $values = [
      [
        'field' => 'field_yabrm_contributor_person',
        'value' => $contrib_id,
      ],
      [
        'field' => 'field_yabrm_contributor_role',
        'value' => $role_tid,
      ],
    ];

    $contributors[] = createParagraph('yabrm_bibliographic_contributor', $values);
  }

  return $contributors;
}

/**
 * Check if a taxonomy term exists.
 *
 * @param string $value
 *   The name of the term.
 * @param string $field
 *   The field to match when validating.
 * @param string $vocabulary
 *   The vid to match.
 *
 * @return mixed
 *   Contains an INT of the tid if exists, FALSE otherwise.
 */
function taxTermExists($value, $field, $vocabulary) {
  $query = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->getQuery();
  $query->condition('vid', $vocabulary);
  $query->condition($field, $value);
  $tids = $query->accessCheck(FALSE)->execute();

  if (!empty($tids)) {
    foreach ($tids as $tid) {
      return $tid;
    }
  }
  return FALSE;
}

/**
 * Create a new Paragraph.
 *
 * @param string $type
 *   Type of Paragraph.
 * @param array $values
 *   Associative array of values to be added to Paragraph.
 *
 * @return int
 *   ID of created Paragraph.
 */
function createParagraph($type, array $values) {
  $paragraph = Paragraph::create(['type' => $type]);

  foreach ($values as $value) {
    $paragraph->set($value['field'], $value['value']);
  }

  // Migrate all paragraphs as unpublished.
  $paragraph->set('status', FALSE);
  $paragraph->save();
  return $paragraph;
}

function full_title($title, &$entity) {
  return $title;
}

// $arg1 = $extra[0];

// echo "\n$arg1\n";