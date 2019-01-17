<?php

namespace Drupal\instance_initial_content\Event;

use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigratePostRowSaveEvent;
use Drupal\migrate\Row;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\yabrm\Entity\BibliographicContributor;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Defines the migrate event subscriber.
 */
class ReferenceMigrateParagraphEvent implements EventSubscriberInterface {

  const MIGRATION_ID = '0_instance_initial_content_references';

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [MigrateEvents::POST_ROW_SAVE => [['onPostRowSave']]];
  }

  /**
   * Subscribed event callback: MigrateEvents::POST_ROW_SAVE.
   *
   * @param \Drupal\migrate\Event\MigratePostRowSaveEvent $event
   *   The event triggered.
   */
  public function onPostRowSave(MigratePostRowSaveEvent $event) {
    $migration = $event->getMigration();
    $migration_id = $migration->id();

    // Only act on rows for this migration.
    if ($migration_id == self::MIGRATION_ID) {
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
      $reference = \Drupal::entityTypeManager()
        ->getStorage('yabrm_biblio_reference')
        ->load($reference_id);

      $reference->setContributors($contributors);
      $reference->save();
    }
  }

  /**
   * Create contributor paragraph entities for a row.
   *
   * @param \Drupal\migrate\Row $row
   *   The row of the migration.
   * @param string $contrib_role
   *   The contributor role.
   *
   * @return \Drupal\paragraphs\Entity\Paragraph[]
   *   The created paragraph entities
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function createContributors(Row $row, $contrib_role) {
    // Contributors.
    $contrib_ids = [];
    $contrib_names = explode(";", $row->getSourceProperty($contrib_role));

    // Create contrib.
    foreach ($contrib_names as $contrib_name) {
      // Trim whitespace and remove periods.
      $contrib_name = trim($contrib_name, "\x2E");

      if (!empty($contrib_name)) {
        $existing = \Drupal::entityQuery('yabrm_contributor')
          ->condition('name', $contrib_name)
          ->execute();

        reset($existing);
        $contrib_id = key($existing);

        // Create contributor if doesn't exist.
        if (empty($contrib_id)) {
          $contrib = BibliographicContributor::create([
            'name' => $contrib_name,
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

    foreach ($contrib_ids as $contrib_id) {
      // Create Paragraph.
      $values = [
        [
          'field' => 'field_yabrm_contributor_person',
          'value' => $contrib_id,
        ],
        [
          'field' => 'field_yabrm_contributor_role',
          'value' => $contrib_role,
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
    return \Drupal::entityQuery($type)
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

}
