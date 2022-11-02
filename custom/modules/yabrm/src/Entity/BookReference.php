<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\yabrm\Entity\BibliographicReference;

/**
 * Defines the Book reference entity.
 *
 * @ingroup yabrm
 *
 * @ContentEntityType(
 *   id = "yabrm_book",
 *   label = @Translation("Book reference"),
 *   handlers = {
 *     "storage" = "Drupal\yabrm\BookReferenceStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\yabrm\BookReferenceListBuilder",
 *     "views_data" = "Drupal\yabrm\Entity\BookReferenceViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\yabrm\Form\BookReferenceForm",
 *       "add" = "Drupal\yabrm\Form\BookReferenceForm",
 *       "edit" = "Drupal\yabrm\Form\BookReferenceForm",
 *       "delete" = "Drupal\yabrm\Form\BookReferenceDeleteForm",
 *     },
 *     "access" = "Drupal\yabrm\BookReferenceAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\yabrm\BookReferenceHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "yabrm_book",
 *   revision_table = "yabrm_book_revision",
 *   revision_data_table = "yabrm_book_field_revision",
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log",
 *   },
 *   admin_permission = "administer book reference entities",
 *   show_revision_ui = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/yabrm/yabrm_book/{yabrm_book}",
 *     "add-form" = "/yabrm/yabrm_book/add",
 *     "edit-form" = "/yabrm/yabrm_book/{yabrm_book}/edit",
 *     "delete-form" = "/yabrm/yabrm_book/{yabrm_book}/delete",
 *     "version-history" = "/yabrm/yabrm_book/{yabrm_book}/revisions",
 *     "revision" = "/yabrm/yabrm_book/{yabrm_book}/revisions/{yabrm_book_revision}/view",
 *     "revision_revert" = "/yabrm/yabrm_book/{yabrm_book}/revisions/{yabrm_book_revision}/revert",
 *     "revision_delete" = "/yabrm/yabrm_book/{yabrm_book}/revisions/{yabrm_book_revision}/delete",
 *     "collection" = "/yabrm/yabrm_book",
 *   },
 *   field_ui_base_route = "yabrm_book.settings"
 * )
 */
class BookReference extends BibliographicReference implements BookReferenceInterface {

  /**
   * {@inheritdoc}
   */
  public function getPlace() {
    return $this->get('place')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPlace($place) {
    $this->set('place', $place);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getEdition() {
    return $this->get('edition')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setEdition($edition) {
    $this->set('edition', $edition);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSeries() {
    return $this->get('series')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSeries($series) {
    $this->set('series', $series);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSeriesNumber() {
    return $this->get('series_number')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSeriesNumber($series_number) {
    $this->set('series_number', $series_number);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getNumberOfVolumes() {
    return $this->get('num_volumes')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setNumberOfVolumes($num_volumes) {
    $this->set('num_volumes', $num_volumes);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getVolume() {
    return $this->get('volume')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setVolume($volume) {
    $this->set('volume', $volume);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getNumberOfPages() {
    return $this->get('num_pages')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setNumberOfPages($num_pages) {
    $this->set('num_pages', $num_pages);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getIsbn() {
    return $this->get('isbn')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setIsbn($isbn) {
    $this->set('isbn', $isbn);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['isbn'] = BaseFieldDefinition::create('string')
      ->setLabel(t('ISBN'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 512,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['num_pages'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Number of Pages'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 512,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['volume'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Volume'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 512,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['num_volumes'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Number of Volumes'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 512,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['series'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Series'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 512,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['series_number'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Series Number'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 512,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['place'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Place'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 512,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['edition'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Edition'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 512,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
