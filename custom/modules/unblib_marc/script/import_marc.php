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
  'author' => [
    'marc' => '100$a',
    'target' => 'contributors',
    'process' => 'create_author',
  ],
  'contributors' => [
    'marc' => '700$a$e',
    'process' => 'create_contribs',
    'multival' => TRUE,
    'append' => TRUE,
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
    'marc' => '700$a$d$e$l',
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

///*
migrateMarc(
  'modules/custom/unblib_marc/data/portolan.mrc',
  'yabrm_book',
  $map,
  FALSE
);
//*/

function migrateMarc(string $source, string $entity_type, array $map, bool $publish) {
  $collection = Collection::fromFile($source);
  $n = 0; // Debug.

  foreach ($collection as $record) {
    $entity = \Drupal::entityTypeManager()->getStorage($entity_type)->create();
    
    foreach ($map as $field => $mapping) {
      $field = $mapping['target'] ?? $mapping['target'] ?? $field;
      $marc = $mapping['marc'] ?? $mapping['marc_fallback'] ?? NULL;
      $multival = isset($mapping['multival']) and $mapping['multival'];
      $append = isset($mapping['append']) and $mapping['append'];

      if ($marc) {
        $value = getMarcValue($record, $marc, $multival);
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
              $value = $callback($value);
            }
          }
        }
        
        if ($append) {
          $update = $entity->get($field)->getValue()[0];
          
          if (is_array($update)) {
            $update[] = $value;
            $entity->set($field, $update);
          }
          else {  
            $entity->set($field, $value);
          }
        }
        else {
          $value = is_string($value) ?
            preg_replace('(^[^\p{L}\p{N}]+|[^\p{L}\p{N}]+$)u', '', $value) :
            $value;
          $entity->set($field, $value);
        }
      }
    }
    $n++; // Debug.
    echo "\n**********";
    
    if ($n == 10) { // Debug.
      exit;
    }
    
    // @TODO: Pass array of mandatory fields and only save if constraints met.
    if ($entity->getTitle()) {
      //$entity->setPublished($publish);
      $entity->setPublished(TRUE);
      $entity->save();
    } 
  }
}

function getMarcValue(
  Record $record, 
  string $marc, 
  bool $multival = FALSE
  ) {
  // Run query.
  $field_data = $record->query($marc);
  $data = "";
  $entries = 0;

  foreach ($field_data as $entry) {
    if ($entry) {
      $entry_data = $entry->__toString();
    }
    // preg_replace('(^[^\p{L}\p{N}]+|[^\p{L}\p{N}]+$)u', '', $entry_data) : $entry_data;
    // Restore trailing period if last character appears to be an initial.
    $entry_data = (is_string($entry_data) and  substr($entry_data, -2, 1) == ' ') ? "$entry_data." : $entry_data;
    // Concatenate data string.
    $data .= $multival ? trim($entry_data) : trim(substr($entry_data, 5));
    $entries++;
  }

  return $data;
}

function date2dmy($date) {
  preg_match('~\b\d{4}\b\+?~', $date, $year);

  if (isset($year[0])) {
    return $year[0];
  }
  
  return;
}

function create_author($author_name) {
  $author = parseSub('a', $author_name);
    echo "\n";
    echo var_dump($author);
  $id = createContributors([$author], 'Author');
  return $id;
}

function create_contribs($contribs_blob) {
  /*
  $name = parseSub('a', $contribs_blob);
  $role = ucwords(parseSub('e', $contribs_blob));
  $id = createContributors([$name], $role);
  return $id;
  */
}

function parseSub($subfield, $data) {
  $pattern = "/\[$subfield\]: (.*?)\[/";
  $results = preg_match_all($pattern, $data, $matches);

  if ($results) {
    if (count($matches) == 1) {
      return $matches[1][0];
    }
    else {
      return $matches[1];
    }
  }
  else {
    return $data;
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
          //'status' => FALSE,
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