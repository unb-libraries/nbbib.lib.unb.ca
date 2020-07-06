<?php

namespace Drupal\instance_initial_content\Event;

use Drupal\instance_initial_content\NbBibMigrationTrait;
use Drupal\migrate_plus\Event\MigrateEvents;
use Drupal\migrate_plus\Event\MigratePrepareRowEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Defines the migrate event subscriber.
 */
class ReferenceMigrateEvent implements EventSubscriberInterface {

  use NbBibMigrationTrait;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[MigrateEvents::PREPARE_ROW][] = ['onPrepareRow', 0];
    return $events;
  }

  /**
   * React to a new row.
   *
   * @param \Drupal\migrate_plus\Event\MigratePrepareRowEvent $event
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
        if (strstr($language, 'french') || strstr('french', $language)) {
          $language = 'fre';
        }
        elseif (strstr($language, 'spanish') || strstr('spanish', $language)) {
          $language = 'spa';
        }
        elseif (strstr($language, 'german') || strstr('german', $language)) {
          $language = 'deu';
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
        else {
          $language = 'eng';
        }
      }
      else {
        $language = 'eng';
      }

      $row->setSourceProperty('language', $language);

      // URL.
      $source_url = $row->getSourceProperty('url');
      $uri = strpos($source_url, 'http') ? $source_url : NULL;

      $url = [
        'uri' => $uri,
        'title' => "Zotero URL",
        'options' => [],
      ];

      $row->setSourceProperty('url', $url);
    }
  }

}
