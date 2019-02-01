<?php

namespace Drupal\instance_initial_content\Event;

use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigratePostRowSaveEvent;
use Drupal\migrate\Row;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\taxonomy\Entity\Term;
use Drupal\yabrm\Entity\BibliographicContributor;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Defines the migrate event subscriber.
 */
class ReferenceMigrateParagraphEvent implements EventSubscriberInterface {

  const APPLICABLE_MIGRATION_IDS = [
    '1_journal_article_references',
    '2_book_references',
    '3_book_section_references',
    '4_thesis_references',
  ];

  const REFERENCE_TYPE_MAPPING = [
    'journalArticle' => 'yabrm_journal_article',
    'book' => 'yabrm_book',
    'bookSection' => 'yabrm_book_section',
    'thesis' => 'yabrm_thesis',
  ];

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [MigrateEvents::POST_ROW_SAVE => [['onPostRowSave', 0]]];
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
    if (in_array($migration_id, self::APPLICABLE_MIGRATION_IDS)) {
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
      $entity_type = self::REFERENCE_TYPE_MAPPING[$item_type];

      $reference = \Drupal::entityTypeManager()
        ->getStorage($entity_type)
        ->load($reference_id);

      // Use source publication year if empty from publication date.
      $pub_year = trim($row->getSourceProperty('publication_year'));
      $src_year = $row->getSourceProperty('src_publication_year');

      if (empty($pub_year)) {
        $pub_year = !empty($src_year) ? $src_year : NULL;
      }

      $reference->setPublicationYear($pub_year);
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
      // Ensure consistent name casing.
      $contrib_name = ucwords(mb_strtolower($contrib_name));

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
    $query = \Drupal::entityQuery('taxonomy_term');
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
