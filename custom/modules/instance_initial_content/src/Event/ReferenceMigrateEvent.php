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

      // Authors.
      $author_ids = [];
      $fieldname = 'name';
      $authors = explode(";", $row->getSourceProperty('author'));

      foreach ($authors as $author) {
        $author = trim($author);

        if (!empty($author)) {
          $author_id = $this->authorExists($author);

          if (!empty($author_id)) {
            // Load author...
          }
          else {
            // Create and save author...
          }

          $author_ids[] = $author_id;
        }
      }

      // Create author Paragraphs using ids...
      // Attach Paragraphs to ulti-value field in bibliographic reference...
      // Authors END.
      TRUE;
    }
  }

  /**
   * Check if an author exists.
   *
   * @param string $value
   *   The name of the author.
   *
   * @return mixed
   *   Contains an INT of the tid if exists, FALSE otherwise.
   */
  public function taxtermExists($value) {
    TRUE;
  }

}
