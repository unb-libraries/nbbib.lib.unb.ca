<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\yabrm\Entity\BibliographicReference;

/**
 * Defines the Periodical reference entity.
 *
 * @ingroup yabrm
 *
 * @ContentEntityType(
 *   id = "yabrm_periodical",
 *   label = @Translation("Periodical reference"),
 *   handlers = {
 *     "storage" = "Drupal\yabrm\PeriodicalReferenceStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\yabrm\PeriodicalReferenceListBuilder",
 *     "views_data" = "Drupal\yabrm\Entity\PeriodicalReferenceViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\yabrm\Form\PeriodicalReferenceForm",
 *       "add" = "Drupal\yabrm\Form\PeriodicalReferenceForm",
 *       "edit" = "Drupal\yabrm\Form\PeriodicalReferenceForm",
 *       "delete" = "Drupal\yabrm\Form\PeriodicalReferenceDeleteForm",
 *     },
 *     "access" = "Drupal\yabrm\PeriodicalReferenceAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\yabrm\PeriodicalReferenceHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "yabrm_periodical",
 *   revision_table = "yabrm_periodical_revision",
 *   revision_data_table = "yabrm_periodical_field_revision",
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log",
 *   },
 *   admin_permission = "administer periodical reference entities",
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
 *     "canonical" = "/yabrm/yabrm_periodical/{yabrm_periodical}",
 *     "add-form" = "/yabrm/yabrm_periodical/add",
 *     "edit-form" = "/yabrm/yabrm_periodical/{yabrm_periodical}/edit",
 *     "delete-form" = "/yabrm/yabrm_periodical/{yabrm_periodical}/delete",
 *     "version-history" = "/yabrm/yabrm_periodical/{yabrm_periodical}/revisions",
 *     "revision" = "/yabrm/yabrm_periodical/{yabrm_periodical}/revisions/{yabrm_periodical_revision}/view",
 *     "revision_revert" = "/yabrm/yabrm_periodical/{yabrm_periodical}/revisions/{yabrm_periodical_revision}/revert",
 *     "revision_delete" = "/yabrm/yabrm_periodical/{yabrm_periodical}/revisions/{yabrm_periodical_revision}/delete",
 *     "collection" = "/yabrm/yabrm_periodical",
 *   },
 *   field_ui_base_route = "yabrm_periodical.settings"
 * )
 */
class PeriodicalReference extends BibliographicReference implements PeriodicalReferenceInterface {

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
  public function getOrganization() {
    return $this->get('organization')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setOrganization($organization) {
    $this->set('organization', $organization);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getIssn() {
    return $this->get('issn')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setIssn($issn) {
    $this->set('issn', $issn);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFirstYear() {
    return $this->get('first_year')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFirstYear($first_year) {
    $this->set('first_year', $first_year);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['organization'] = BaseFieldDefinition::create('string')
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

    $fields['place'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Place'))
      ->setDescription(t('Place of publication for the item.'))
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

    $fields['issn'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Periodical Type'))
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

    $fields['first_year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('First Publication Year'))
      ->setRevisionable(TRUE)
      ->addPropertyConstraints('value', [
        'Range' => [
          'min' => 1000,
          'max' => 2048,
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 4,
      ])
      ->setDisplayOptions('form', [
        'weight' => 4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
