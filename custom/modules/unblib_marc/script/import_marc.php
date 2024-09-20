<?php

use Drupal\paragraphs\Entity\Paragraph;
use Drupal\taxonomy\Entity\Term;
use Drupal\yabrm\Entity\BibliographicCollection;
use Drupal\yabrm\Entity\BibliographicContributor;
use Scriptotek\Marc\Collection;
use Scriptotek\Marc\Fields\Field;
use Scriptotek\Marc\Record;

$source = 'modules/custom/unblib_marc/data/portolan.mrc';
$collection = Collection::fromFile($source);
$yabrm_collection = create_yabrm_collection("Children's Literature");

$map = [
  'collections' => [
    'default' => $yabrm_collection,
  ],
  'external_key_ref' => [
    'marc' => '001',
    'marc_fallback' => '020$a',
    'default' => 'NO_EXTERNAL_KEY',
  ],
  'extra' => [
    'marc' => '001',
    'process' => 'create_extra'
  ],
  'title' => [
    'marc' => '245$a',
    'process' => 'create_title',
  ],
  'abstract_note' => [
    'marc' => '590$a',
    'marc_fallback' => '520$a',
    'process' => 'create_abstract',
  ],
  'publication_year' => [
    'marc' => '260$c',
    'marc_fallback' => '264$c',
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
    'process' => 'text_trim_sentence',
  ],
  'language' => [
    'marc' => '041$a',
    'process' => 'marc2lang',
  ],
  'archive' => [
    'marc' => '850$a',
    'process' => 'text_trim',
  ],
  'archive_location' => [
    'marc' => '852$a',
    'process' => 'text_trim',
  ],
  'library_catalog' => [
    'marc' => '040$a',
    'process' => 'text_trim',
  ],  
  'call_number' => [
    'marc' => '852',
    'process' => 'create_callno',
  ],
  'notes' => [
    'marc' => '245$c',
    'process' => 'text_trim_sentence',
  ],
  'isbn' => [
    'marc' => '020$a',
    'process' => 'parse_isbn',
    'multival' => TRUE,
  ],
  'volume' => [
    'marc' => '490$v',
    'process' => 'text_trim',
  ],
  'series' => [
    'marc' => '490$a',
    'process' => 'text_trim',
  ],
  'publisher' => [
    'marc' => '260$b',
    'marc_fallback' => '264$b',
    'process' => 'text_trim',
  ],
  'place' => [
    'marc' => '260$a',
    'marc_fallback' => '264$a',
    'process' => 'text_trim_sentence',
  ],
  'edition' => [
    'marc' => '250$a',
    'process' => 'text_trim',
  ],
  'physical_description' => [
    'marc' => '300$a$b$c',
    'process' => 'create_physical',
    'multival' => TRUE,
  ],
  'location' => [
    'marc' => '691$a',
    'target' => 'topics',
    'process' => 'create_topic',
    'append' => TRUE,
  ],
  'descriptor' => [
    'marc' => '690$a',
    'target' => 'topics',
    'process' => 'create_descriptors',
    'append' => TRUE,
  ],
  'location' => [
    'marc' => '691$a',
    'target' => 'topics',
    'process' => 'create_descriptors',
    'append' => TRUE,
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
  $collection = Collection::fromFile($source)->toArray();
  $collection = array_reverse($collection);
  $n = 0;

  foreach ($collection as $record) {
    $callno = create_callno('', $record, $entity = NULL);
    $last_callno = $callno == '' ? $last_callno : $callno;
    $jurisdiction = '';
    $jurisdictions = $record->query('593');

    foreach ($jurisdictions as $entry) {
      $jurisdiction .= $entry;
    }

    $filter = str_contains(strtolower($jurisdiction), 'new brunswick') and
      str_contains(strtolower($jurisdiction), 'jurisdiction');
    
    if ($filter) {
      $entity = \Drupal::entityTypeManager()->getStorage($entity_type)->create();
      
      foreach ($map as $field => $mapping) {
        $field = isset($mapping['target']) ? $mapping['target'] : $field;
        $marc = $mapping['marc'] ?? NULL;
        $fallback = $mapping['marc_fallback'] ?? NULL; 
        $multival = isset($mapping['multival']) and $mapping['multival'];
        $append = isset($mapping['append']) and $mapping['append'];
        
        if ($marc) {
          $value = getMarcValue($record, $marc, $multival);
          $og_value = $value;

          if (!$value and $fallback) {
            $value = getMarcValue($record, $fallback, $multival);
          }
        }
        elseif ($mapping['default']) {
          $value = $mapping['default'];
        }
        
        if ($marc == '852') {
          $value = trim($last_callno);
          unset($mapping['process']);
          $append = FALSE;
        }

        if ($value) {
          
          if (isset($mapping['process'])) {
            $callback = $mapping['process'];
            if (is_callable($callback)) {
              if (function_exists($callback)) {
                $value = $callback($value, $record, $entity);
              }
            }
          }
          
          if ($append) {
            $update = $entity->get($field)->getValue();
            
            if (is_array($update)) {
              $update = array_merge($update, $value);
              $entity->set($field, $update);
            }
            else {  
              $entity->set($field, $value);
            }
            
          }
          else {            
            $entity->set($field, $value);
          }
        }
      }

      $title = $entity->getTitle();
      
      // @TODO: Pass array of mandatory fields and only save if constraints met.
      if ($title) {
        echo "\nSaving unpublished [$entity_type] [$title]\n";
        //$entity->setPublished($publish);
        $entity->save();
      }
    }
    
    //$n++;
    
    if ($n > 5) {
      exit;
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
      $entry_data = trim($entry->__toString());
    }
    // Concatenate data string.
    $data .= $multival ? trim($entry_data) : trim(substr($entry_data, 5));
    $entries++;
  }
  
  return $data;
}
  
function create_title($data, $record, $entity) {
  $title = $data;
  $title = $title ? text_trim_sentence($title) : NULL;
  $subtitle = getMarcValue($record, '245$b');
  $subtitle = $subtitle ? text_trim_sentence($subtitle) : NULL;
  $full_title = '';
  
  if ($title) {
    $full_title .= $title;
    
    if ($subtitle) {
      $full_title = "$full_title: $subtitle";
    }
  }

  $full_title = $full_title ? $full_title : $data;
  
  return text_trim($full_title, $entity, TRUE);
}
  
function date2dmy($date, $record, $entity) {
  $found = preg_match('/\d+/', $date, $year);
  
  if (isset($year[0])) {    
    return $year[0];
  }

  return $date;
}
  
function create_author($author_name, $record, $entity) {
  $author = parseRecord('a', $author_name);
  $author = substr($author, -1) == ',' ? substr($author, 0, -1) : $author; 
  $author = ucwords(text_trim($author));
  $author = (substr($author, -2, 1) == ' ') ? "$author." : $author;
  $paragraph = createContributors([$author], 'Author')[0];
  $id = $paragraph->id();
  $rid = $paragraph->getRevisionId();
  
  $ref = [
    'target_id' => $id,
    'target_revision_id' => $rid,
  ];
  
  return $ref;
}

function create_contribs($contribs_blob, $record, $entity) {
  $paragraphs = $entity->get('contributors')->referencedEntities();
  $existing = [];

  foreach ($paragraphs as $paragraph) {
    $contrib = $paragraph->field_yabrm_contributor_person->referencedEntities();
    $role_term = $paragraph->field_yabrm_contributor_role->referencedEntities();
    $name = $contrib[0]->getName();
    $role = $role_term[0]->name->value;
    $existing[] = [$name, $role];
  }

  $results = parseRecord('a', $contribs_blob);
  $refs = [];
  $prev_name = '';
  
  foreach ($results as $result) {
    $name = parseSub('a', $result);
    $role = parseSub('e', $result);
    
    if ($name) {
      $name = substr($name, -1) == ',' ? substr($name, 0, -1) : $name; 
      $name = ucwords(text_trim($name));
      $name = substr($name, -2, 1) == ' ' ? "$name." : $name;
      $role = $role ? $role : 'Unknown';
      $role = ucwords(text_trim($role));

      if (!in_array([$name, $role], $existing)
        and !($role == 'Unknown' and in_array($name, array_column($existing, 0)))) {
        $paragraph = createContributors([$name], $role)[0];
        $id = $paragraph->id();
        $rid = $paragraph->getRevisionId();
        
        $refs[] = [
          'target_id' => $id,
          'target_revision_id' => $rid,
        ];

        $existing[] = [$name, $role];
      }
    }
  }

  return $refs;
}

function parse_isbn($data, $record, $entity) {
  $records = parseRecord('a', $data);
  
  foreach ($records as $record) {
    $isbn = parseSub('a', $record);

    if (strlen($isbn) == 13) {
      return $isbn;
    }
  }

  return $data;
}

function create_physical($data, $record, $entity) {
  $pages = parseSub('a', $data);
  $pages = $pages ? text_period(ucfirst(strtolower(text_trim_sentence($pages)))) : NULL;
  $details = parseSub('b', $data);
  $details = $details ? text_period(ucfirst(strtolower(text_trim_sentence($details)))) : NULL;
  $dimensions = parseSub('c', $data);
  $dimensions = $dimensions ? text_period(ucfirst(text_trim_sentence(text_trim($dimensions)))) : NULL;
  $physical = trim("$details $dimensions $pages") ?? $data;
  
  return $physical;
}

function marc2lang($language, $record, $entity) {
  if (!empty($language)) {
    if (strstr($language, 'ara') || strstr('ara', $language)) {
      $language = 'ara';
    }
    elseif (strstr($language, 'deu') || strstr('deu', $language)) {
      $language = 'deu';
    }
    elseif (strstr($language, 'fre') || strstr('fre', $language)) {
      $language = 'fre';
    }
    elseif (strstr($language, 'lat') || strstr('lat', $language)) {
      $language = 'lat';
    }
    elseif (strstr($language, "mic") || strstr("mic", $language)) {
      $language = 'mic';
    }
    elseif (strstr($language, "pqm") || strstr("pqm", $language)) {
      $language = 'pqm';
    }
    elseif (strstr($language, 'spa') || strstr('spa', $language)) {
      $language = 'spa';
    }
    else {
      $language = 'eng';
    }
  }
  else {
    $language = 'eng';
  }

  return $language;
}

function create_extra($data, $record, $entity) {
  $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
  return "OCLC: $data";
}

function parseRecord($subfield, $data) {
  // If $subfield == 'a', match everything starting with [a]:<space> and ending before [a] or end of string.  
  $pattern = "/\[$subfield\]: (.*?)(?=\[$subfield\]|$)/";
  $results = preg_match_all($pattern, $data, $matches);

  if ($results) {
    if (count($matches) == 1) {
      return $matches[1][0];
    }
    else {
      return array_unique($matches[0]);
    }
  }
  else {
    return $data;
  }
}

function create_abstract($abstract, $record, $entity) {
  $append = getMarcValue($record, '594$a');
  return "$abstract\n\n$append";
}

function create_descriptors($data, $record, $entity) {
  $descriptors = explode('.', $data);
  $topics = [];
  
  foreach ($descriptors as $desc) {

    if ($desc) {
      $term = create_topic($desc);
      $topics[] = $term;
    }
  }

  return $topics;
}

function create_callno($data, $record, $entity) {
  $call_number = '';

  foreach ($record->getFields('852') as $subfields) {
    foreach ($subfields->getSubfields() as $code => $value) {
      if ($code == 'c' || $code == 'h' || $code == 'i') {
        $call_number .= " {$value->getData()}";
      }
    }
  }

  return($call_number);
}

function create_topic($data) {
  $topic = text_trim($data);
  $voc = 'yabrm_reference_topic';
  $field = 'name';
  $tid = taxTermExists($topic, $field, $voc);
  
  if (!$tid) {
    $term = Term::create([
      'vid' => $voc,
      $field => $topic,
    ]);
    
    $term->save();
    $tid = $term->id();
  }
  
  $ref = ['target_id' => $tid];
  return $ref;
}

function parseSub($subfield, $data) {
  if (!str_contains($data, "[$subfield]")) {
    return NULL;
  }
  // If $subfield == 'a', match everything starting with [a]:<space> and ending before [ or end of string.  
  $pattern = "/\[$subfield\]: (.*?)(?=\[[a-z]\]|$)/";
  $results = preg_match($pattern, $data, $matches);

  if ($results) {
    return $matches[1];
  }
  else {
    return $data;
  }
}

function text_trim_sentence(string $text) {
  return text_trim($text, NULL, NULL, TRUE);
}

function text_trim(string $text, $record = NULL, $entity = NULL, bool $sentence = FALSE) {
  $first = substr($text, 0, 1);
  $last = substr($text, -1);
  $starters = $sentence ? ["'", '"', '(', '['] : [];
  $enders = $sentence ? ['.', '!', '?' , "'", '"', ')', ']'] : [];
  
  while (!ctype_alnum($first) and (!in_array($first, $starters) or substr($text, 1, 1) == ' ')) {
    $text = substr($text, 1);
    $first = substr($text, 0, 1);
  }
  
  while (!ctype_alnum($last) and (!in_array($last, $enders) or substr($text, -2) == ' ')) {
    $text = substr($text, 0, -1);
    $last = substr($text, -1);
  }

  return $text;
}

function text_period(string $text) {
  $text = (substr($text, -1) == '.') ? $text : "$text.";
  return $text;
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

    if ($contrib_name) {
      $existing = \Drupal::entityTypeManager()->getStorage('yabrm_contributor')
        ->getQuery()
        ->condition('zotero_name', $zotero_name)
        ->accessCheck(FALSE)
        ->execute();

      reset($existing);
      $contrib_id = current($existing);

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
  //$paragraph->set('status', FALSE);
  $paragraph->save();
  
  return $paragraph;
}

function create_yabrm_collection($collection_name) {
  $existing = \Drupal::entityTypeManager()->getStorage('yabrm_collection')
    ->getQuery()
    ->condition('name', $collection_name)
    ->accessCheck(FALSE)
    ->execute();

  reset($existing);
  $col_id = key($existing);

  // Create collection if doesn't exist.
  if (empty($col_id)) {
    $collection = BibliographicCollection::create([
      'name' => $collection_name,
    ]);

    $collection->save();
  }
  else {
    $collection = BibliographicCollection::load($col_id);
  }

  return $collection;
}