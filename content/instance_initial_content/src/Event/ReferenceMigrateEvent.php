<?php

namespace Drupal\instance_initial_content\Event;

use Drupal\migrate_plus\Event\MigrateEvents;
use Drupal\migrate_plus\Event\MigratePrepareRowEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Defines the migrate event subscriber.
 */
class ReferenceMigrateEvent implements EventSubscriberInterface {

  const MIGRATION_ID = '0_instance_initial_content_references';

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
    if ($migration_id == self::MIGRATION_ID) {

      // Publication Date.
      $pub_date = explode('-', $row->getSourceProperty('publication_date'));

      if (!empty($pub_date[0])) {
        $row->setSourceProperty('publication_year', (int) $pub_date[0]);
      }
      if (!empty($pub_date[1])) {
        $row->setSourceProperty('publication_month', (int) $pub_date[1]);
      }
      if (!empty($pub_date[2])) {
        $row->setSourceProperty('publication_day', (int) $pub_date[2]);
      }

      // Language.
      $language = strtolower($row->getSourceProperty('language'));

      if (!empty($language)) {
        if (strstr($language, 'french') || strstr('french', $language)) {
          $language = 'fre';
        }
        else {
          $language = 'eng';
        }
      }
      else {
        $language = 'eng';
      }

      $row->setSourceProperty('language', $language);
    }
  }

}
