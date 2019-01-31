<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\yabrm\Entity\BibliographicReference;

/**
 * Defines the Thesis reference entity.
 *
 * @ingroup yabrm
 *
 * @ContentEntityType(
 *   id = "yabrm_thesis",
 *   label = @Translation("Thesis reference"),
 *   handlers = {
 *     "storage" = "Drupal\yabrm\ThesisReferenceStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\yabrm\ThesisReferenceListBuilder",
 *     "views_data" = "Drupal\yabrm\Entity\ThesisReferenceViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\yabrm\Form\ThesisReferenceForm",
 *       "add" = "Drupal\yabrm\Form\ThesisReferenceForm",
 *       "edit" = "Drupal\yabrm\Form\ThesisReferenceForm",
 *       "delete" = "Drupal\yabrm\Form\ThesisReferenceDeleteForm",
 *     },
 *     "access" = "Drupal\yabrm\ThesisReferenceAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\yabrm\ThesisReferenceHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "yabrm_thesis",
 *   revision_table = "yabrm_thesis_revision",
 *   revision_data_table = "yabrm_thesis_field_revision",
 *   admin_permission = "administer thesis reference entities",
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
 *     "canonical" = "/yabrm/yabrm_thesis/{yabrm_thesis}",
 *     "add-form" = "/yabrm/yabrm_thesis/add",
 *     "edit-form" = "/yabrm/yabrm_thesis/{yabrm_thesis}/edit",
 *     "delete-form" = "/yabrm/yabrm_thesis/{yabrm_thesis}/delete",
 *     "version-history" = "/yabrm/yabrm_thesis/{yabrm_thesis}/revisions",
 *     "revision" = "/yabrm/yabrm_thesis/{yabrm_thesis}/revisions/{yabrm_thesis_revision}/view",
 *     "revision_revert" = "/yabrm/yabrm_thesis/{yabrm_thesis}/revisions/{yabrm_thesis_revision}/revert",
 *     "revision_delete" = "/yabrm/yabrm_thesis/{yabrm_thesis}/revisions/{yabrm_thesis_revision}/delete",
 *     "collection" = "/yabrm/yabrm_thesis",
 *   },
 *   field_ui_base_route = "yabrm_thesis.settings"
 * )
 */
class ThesisReference extends BibliographicReference implements ThesisReferenceInterface {

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
  public function getThesisType() {
    return $this->get('thesis_type')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setThesisType($thesis_type) {
    $this->set('thesis_type', $thesis_type);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

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

    $fields['thesis_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Thesis Type'))
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
