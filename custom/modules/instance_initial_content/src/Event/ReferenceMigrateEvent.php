<?php

namespace Drupal\instance_initial_content\Event;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\instance_initial_content\NbBibMigrationTrait;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigrateImportEvent;
use Drupal\migrate_plus\Event\MigrateEvents as MigratePlusEvents;
use Drupal\migrate_plus\Event\MigratePrepareRowEvent;
use Drupal\yabrm\Entity\BibliographicCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Defines the migrate event subscriber.
 */
class ReferenceMigrateEvent implements EventSubscriberInterface {

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
    $events[MigratePlusEvents::PREPARE_ROW][] = ['onPrepareRow', 0];
    $events[MigrateEvents::POST_IMPORT][] = ['onPostImport', 0];
    return $events;
  }

  /**
   * React to a new row.
   *
   * @param Drupal\migrate_plus\Event\MigratePrepareRowEvent $event
   *   The prepare-row event.
   */
  public function onPrepareRow(MigratePrepareRowEvent $event) {
    $row = $event->getRow();
    $migration = $event->getMigration();
    $migration_id = $migration->id();

    // Only act on rows for this migration.
    if (array_key_exists($migration_id, self::getMigrations())) {
      // Publication Date.
      $pub_date_raw = $row->getSourceProperty('publication_date');

      if (!is_numeric(str_replace("-", "", $pub_date_raw))) {
        $row->setSourceProperty('notes', $pub_date_raw);
      }
      else {
        $pub_date = explode('-', $pub_date_raw);

        if (!empty($pub_date[0])) {
          $pub_year = (int) $pub_date[0];
          $pub_year = $pub_year > 0 ? $pub_year : NULL;
          $row->setSourceProperty('publication_year', $pub_year);
        }
        if (!empty($pub_date[1])) {
          $pub_mon = (int) $pub_date[1];
          $pub_mon = $pub_mon > 0 ? $pub_mon : NULL;
          $row->setSourceProperty('publication_month', $pub_mon);
        }
        if (!empty($pub_date[2])) {
          $pub_day = (int) $pub_date[2];
          $pub_day = $pub_day > 0 ? $pub_day : NULL;
          $row->setSourceProperty('publication_day', $pub_day);
        }
      }

      // Make sure no zero publication dates get passed.
      $pub_year_out = (int) $row->getSourceProperty('publication_year');

      if ($pub_year_out == 0) {
        $row->setSourceProperty('publication_year', NULL);
        $row->setSourceProperty('publication_month', NULL);
        $row->setSourceProperty('publication_day', NULL);
      }

      // Language.
      $language = strtolower($row->getSourceProperty('language'));

      if (!empty($language)) {
        if (strstr($language, 'arabic') || strstr('arabic', $language)) {
          $language = 'ara';
        }
        elseif (strstr($language, 'german') || strstr('german', $language)) {
          $language = 'deu';
        }
        elseif (strstr($language, 'french') || strstr('french', $language)) {
          $language = 'fre';
        }
        elseif (strstr($language, 'latin') || strstr('latin', $language)) {
          $language = 'lat';
        }
        elseif (strstr($language, "mi'kmaq") || strstr("mi'kmaq", $language) ||
                strstr($language, "mi'kmaw") || strstr("mi'kmaw", $language) ||
                strstr($language, "micmac") || strstr("micmac", $language)) {
          $language = 'mic';
        }
        elseif (strstr($language, "wolastoqey") || strstr("wolastoqey", $language) ||
                strstr($language, "passamaquoddy") || strstr("passamaquoddy", $language) ||
                strstr($language, "maliseet") || strstr("maliseet", $language) ||
                strstr($language, "malecite") || strstr("malecite", $language)) {
          $language = 'pqm';
        }
        elseif (strstr($language, 'spanish') || strstr('spanish', $language)) {
          $language = 'spa';
        }
        else {
          $language = 'eng';
        }
      }
      else {
        $language = 'eng';
      }

      $row->setSourceProperty('language', $language);

      // Number of pages.
      $source_pages = trim($row->getSourceProperty('num_pages'));

      if (is_numeric($source_pages)) {
        // If pages value is an integer, just pass to corresponding field.
        $row->setSourceProperty('num_pages', $source_pages);
      }
      else {
        // Otherwise try to split string on double slash delimiter.
        $split_pages = explode('//', $source_pages);

        // If delimiter not in string or string before delimiter not numeric...
        if ($split_pages[0] == $source_pages || !is_numeric($split_pages[0])) {
          // Pass full string to physical description.
          $row->setSourceProperty('physical_description', $source_pages);
          // Clear default source num_pages (contains direct migrated value).
          $row->setSourceProperty('num_pages', NULL);
        }
        else {
          // Otherwise pass substring before delimiter to pages...
          $row->setSourceProperty('num_pages', $split_pages[0]);
          // And the rest to physical description.
          $row->setSourceProperty('physical_description', $split_pages[1]);
        }
      }
    }
  }

  /**
   * React to post-import.
   *
   * @param Drupal\migrate\Event\MigrateImportEvent $event
   *   The post-import event.
   */
  public function onPostImport(MigrateImportEvent $event) {
    // Create default manual collection 'Bibliographies'.
    $collection_name = 'Bibliographies';

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
    }
  }

}
