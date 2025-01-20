<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\yabrm\Entity\BibliographicReference;

/**
 * Defines the Conference reference entity.
 *
 * @ingroup yabrm
 *
 * @ContentEntityType(
 *   id = "yabrm_conference",
 *   label = @Translation("Conference Proceeding reference"),
 *   handlers = {
 *     "storage" = "Drupal\yabrm\ConferenceReferenceStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\yabrm\ConferenceReferenceListBuilder",
 *     "views_data" = "Drupal\yabrm\Entity\ConferenceReferenceViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\yabrm\Form\ConferenceReferenceForm",
 *       "add" = "Drupal\yabrm\Form\ConferenceReferenceForm",
 *       "edit" = "Drupal\yabrm\Form\ConferenceReferenceForm",
 *       "delete" = "Drupal\yabrm\Form\ConferenceReferenceDeleteForm",
 *     },
 *     "access" = "Drupal\yabrm\ConferenceReferenceAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\yabrm\ConferenceReferenceHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "yabrm_conference",
 *   revision_table = "yabrm_conference_revision",
 *   revision_data_table = "yabrm_conference_field_revision",
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log",
 *   },
 *   admin_permission = "administer conference reference entities",
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
 *     "canonical" = "/yabrm/yabrm_conference/{yabrm_conference}",
 *     "add-form" = "/yabrm/yabrm_conference/add",
 *     "edit-form" = "/yabrm/yabrm_conference/{yabrm_conference}/edit",
 *     "delete-form" = "/yabrm/yabrm_conference/{yabrm_conference}/delete",
 *     "version-history" = "/yabrm/yabrm_conference/{yabrm_conference}/revisions",
 *     "revision" = "/yabrm/yabrm_conference/{yabrm_conference}/revisions/{yabrm_conference_revision}/view",
 *     "revision_revert" = "/yabrm/yabrm_conference/{yabrm_conference}/revisions/{yabrm_conference_revision}/revert",
 *     "revision_delete" = "/yabrm/yabrm_conference/{yabrm_conference}/revisions/{yabrm_conference_revision}/delete",
 *     "collection" = "/yabrm/yabrm_conference",
 *   },
 *   field_ui_base_route = "yabrm_conference.settings"
 * )
 */
class ConferenceReference extends BibliographicReference implements ConferenceReferenceInterface {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormat() {
    return $this->get('format')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFormat($format) {
    $this->set('format', $format);
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
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['place'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Place'))
      ->setDescription(t('Place associated to the item.'))
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

    $fields['format'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Format'))
      ->setDescription(t('Format of the conference.'))
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

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('Name of the conference.'))
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
