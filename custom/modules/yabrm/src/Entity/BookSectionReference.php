<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\yabrm\Entity\BibliographicReference;

/**
 * Defines the Book Section reference entity.
 *
 * @ingroup yabrm
 *
 * @ContentEntityType(
 *   id = "yabrm_book_section",
 *   label = @Translation("Book Section reference"),
 *   handlers = {
 *     "storage" = "Drupal\yabrm\BookSectionReferenceStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\yabrm\BookSectionReferenceListBuilder",
 *     "views_data" = "Drupal\yabrm\Entity\BookSectionReferenceViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\yabrm\Form\BookSectionReferenceForm",
 *       "add" = "Drupal\yabrm\Form\BookSectionReferenceForm",
 *       "edit" = "Drupal\yabrm\Form\BookSectionReferenceForm",
 *       "delete" = "Drupal\yabrm\Form\BookSectionReferenceDeleteForm",
 *     },
 *     "access" = "Drupal\yabrm\BookSectionReferenceAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\yabrm\BookSectionReferenceHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "yabrm_book_section",
 *   revision_table = "yabrm_book_section_revision",
 *   revision_data_table = "yabrm_book_section_field_revision",
 *   admin_permission = "administer book_section reference entities",
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
 *     "canonical" = "/yabrm/yabrm_book_section/{yabrm_book_section}",
 *     "add-form" = "/yabrm/yabrm_book_section/add",
 *     "edit-form" = "/yabrm/yabrm_book_section/{yabrm_book_section}/edit",
 *     "delete-form" = "/yabrm/yabrm_book_section/{yabrm_book_section}/delete",
 *     "version-history" = "/yabrm/yabrm_book_section/{yabrm_book_section}/revisions",
 *     "revision" = "/yabrm/yabrm_book_section/{yabrm_book_section}/revisions/{yabrm_book_section_revision}/view",
 *     "revision_revert" = "/yabrm/yabrm_book_section/{yabrm_book_section}/revisions/{yabrm_book_section_revision}/revert",
 *     "revision_delete" = "/yabrm/yabrm_book_section/{yabrm_book_section}/revisions/{yabrm_book_section_revision}/delete",
 *     "collection" = "/yabrm/yabrm_book_section",
 *   },
 *   field_ui_base_route = "yabrm_book_section.settings"
 * )
 */
class BookSectionReference extends BibliographicReference implements BookSectionReferenceInterface {

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
  public function getPages() {
    return $this->get('pages')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPages($pages) {
    $this->set('pages', $pages);
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
  public function getPublicationTitle() {
    return $this->get('publication_title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPublicationTitle($publication_title) {
    $this->set('publication_title', $publication_title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['publication_title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Publication Title'))
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

    $fields['pages'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Pages'))
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