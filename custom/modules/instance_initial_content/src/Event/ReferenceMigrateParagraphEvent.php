<?php

namespace Drupal\instance_initial_content\Event;

use Drupal\instance_initial_content\NbBibMigrationTrait;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigratePostRowSaveEvent;
use Drupal\migrate\Row;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\taxonomy\Entity\Term;
use Drupal\yabrm\Entity\BibliographicContributor;
use Drupal\yabrm\Entity\BibliographicCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Defines the migrate event subscriber.
 */
class ReferenceMigrateParagraphEvent implements EventSubscriberInterface {

  use NbBibMigrationTrait;

  /**
   * Dependency injection for entity_type.manager.
   *
   * @var Drupal\Core\Entity\EntityTypeManager
   */
  protected $typeManager;

  /**
   * Constructs a new ReferenceMigrateParagraphEvent object.
   *
   * @param Drupal\Core\Entity\EntityTypeManager $type_manager
   *   Dependency injection for entity_type.manager.
   */
  public function __construct(EntityTypeManager $type_manager) {
    $this->typeManager = $type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [MigrateEvents::POST_ROW_SAVE => [['onPostRowSave', 0]]];
  }

  /**
   * Subscribed event callback: MigrateEvents::POST_ROW_SAVE.
   *
   * @param Drupal\migrate\Event\MigratePostRowSaveEvent $event
   *   The event triggered.
   */
  public function onPostRowSave(MigratePostRowSaveEvent $event) {
    $migration = $event->getMigration();
    $migration_id = $migration->id();

    // Only act on rows for this migration.
    if (array_key_exists($migration_id, self::getMigrations())) {
      $row = $event->getRow();
      $destination_ids = $event->getDestinationIdValues();
      $reference_id = $destination_ids[0];

      // Contributors.
      $authors = $this->createContributors($row, 'author');
      $editors = $this->createContributors($row, 'editor');
      $series_editors = $this->createContributors($row, 'series_editor');
      $translators = $this->createContributors($row, 'translator');
      $src_contributors = $this->createContributors($row, 'contributor');
      $book_authors = $this->createContributors($row, 'book_author');
      $reviewed_authors = $this->createContributors($row, 'reviewed_author');

      $contributors = array_merge(
        $authors,
        $editors,
        $series_editors,
        $translators,
        $src_contributors,
        $book_authors,
        $reviewed_authors
      );

      // Instance and update reference.
      $item_type = $row->getSourceProperty('item_type');
      $entity_type = self::getZoteroTypeMappings()[$item_type];

      $reference = $this->typeManager
        ->getStorage($entity_type)
        ->load($reference_id);

      // Use source publication year if empty from publication date.
      $pub_year = trim($row->getSourceProperty('publication_year'));
      $src_year = $row->getSourceProperty('src_publication_year');

      if (empty($pub_year)) {
        $pub_year = !empty($src_year) ? $src_year : NULL;
      }

      if ((int) $pub_year == 0) {
        $pub_year = NULL;
      }

      // Default collection.
      $collection_name = self::getMigrations()[$migration_id];

      // If it's a religion migration, add to religion Collection.
      $parts = explode(': ', $collection_name);

      if ($parts[0] == 'Religion') {
        $collection_name = $parts[0];
      }

      if (!empty($collection_name)) {
        $existing = $this->typeManager->getStorage('yabrm_collection')
          ->getQuery()
          ->condition('name', $collection_name)
          ->execute();

        reset($existing);
        $col_id = key($existing);

        // Create collection if doesn't exist.
        if (empty($col_id)) {
          $collection = BibliographicCollection::create([
            'name' => $collection_name,
          ]);

          $collection->save();
          $col_id = $collection->id();
        }
      }

      $collections[] = $col_id ? $col_id : NULL;

      // Archive.
      $arch_name = $row->getSourceProperty('archive');

      if (!empty($arch_name)) {
        $existing = $this->typeManager->getStorage('taxonomy_term')
          ->getQuery()
          ->condition('name', $arch_name)
          ->condition('vid', 'nbbib_archives')
          ->execute();

        reset($existing);
        $arch_id = key($existing);

        // Create archive if doesn't exist.
        if (empty($arch_id)) {
          $archive = Term::create([
            'name' => $arch_name,
            'vid' => 'nbbib_archives',
          ]);

          $archive->save();
          $arch_id = $archive->id();
        }
      }

      $archives[] = $arch_id ? $arch_id : NULL;

      // URL.
      $source_url = $row->getSourceProperty('url');
      $uri = substr(trim($source_url), 0, 4) === 'http' ? $source_url : NULL;

      if ($uri) {

        $url = [
          'uri' => $uri,
          'title' => $uri,
          'options' => [
            'attributes' => [
              'target' => '_blank',
            ],
          ],
        ];

        $reference->setUrl($url);
      }

      $reference->setContributors($contributors);
      $reference->setPublicationYear($pub_year);
      $reference->setCollections($collections);
      $reference->setArchive($archives);
      $reference->setPublished(FALSE);
      $reference->save();
    }
  }

  /**
   * Create contributor paragraph entities for a row.
   *
   * @param Drupal\migrate\Row $row
   *   The row of the migration.
   * @param string $contrib_role
   *   The contributor role.
   *
   * @return Drupal\paragraphs\Entity\Paragraph[]
   *   The created paragraph entities
   *
   * @throws Drupal\Core\Entity\EntityStorageException
   */
  public function createContributors(Row $row, $contrib_role) {
    // Contributors.
    $contrib_ids = [];
    $contrib_names = explode(";", $row->getSourceProperty($contrib_role));

    // Create contrib.
    foreach ($contrib_names as $contrib_name) {
      // Trim whitespace and remove periods.
      $contrib_name = trim($contrib_name, " \t\n\r\0\x0B\x2E");
      // Keep exact trimmed copy to prevent duplicate import.
      $zotero_name = $contrib_name;
      // Ensure consistent name casing.
      $contrib_name = ucwords(mb_strtolower($contrib_name));
      // If contributor is anonymous...
      if (strpos(mb_strtolower($contrib_name), 'anonymous')) {
        $zotero_name = $contrib_name = 'Anonymous';
      }

      if (!empty($contrib_name)) {
        $existing = $this->typeManager->getStorage('yabrm_contributor')
          ->getQuery()
          ->condition('zotero_name', $zotero_name)
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
      $role_tid = $this->taxTermExists($role_term, $field, $voc);

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

      $contributors[] = $this->createParagraph('yabrm_bibliographic_contributor', $values);
    }

    return $contributors;
  }

  /**
   * Check if an entity exists with a specific value in a specific field.
   *
   * @param string $type
   *   The entity type.
   * @param string $field
   *   The field to match the value on.
   * @param string $value
   *   The name of the contrib.
   *
   * @return mixed
   *   An array of INTs of the id if exists, FALSE otherwise.
   */
  public function entitySearch($type, $field, $value) {
    return $this->typeManager->getStorage($type)
      ->getQuery()
      ->condition($field, $value)
      ->execute();
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
  public function createParagraph($type, array $values) {
    $paragraph = Paragraph::create(['type' => $type]);

    foreach ($values as $value) {
      $paragraph->set($value['field'], $value['value']);
    }

    $paragraph->save();
    return $paragraph;
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
  public function taxTermExists($value, $field, $vocabulary) {
    $query = $this->typeManager->getStorage('taxonomy_term')->getQuery();
    $query->condition('vid', $vocabulary);
    $query->condition($field, $value);
    $tids = $query->execute();

    if (!empty($tids)) {
      foreach ($tids as $tid) {
        return $tid;
      }
    }
    return FALSE;
  }

}
