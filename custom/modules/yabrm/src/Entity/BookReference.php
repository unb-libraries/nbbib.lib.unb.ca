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
 *   admin_permission = "administer book reference entities",
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
  public function getPublisher() {
    return $this->get('publisher')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPublisher($publisher) {
    $this->set('publisher', $publisher);
    return $this;
  }

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

    $fields['publisher'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Publisher'))
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