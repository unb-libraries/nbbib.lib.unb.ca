<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Bibliographic Contributor Archival entity.
 *
 * @ingroup yabrm
 *
 * @ContentEntityType(
 *   id = "yabrm_contributor_archival",
 *   label = @Translation("Bibliographic Contributor Archival"),
 *   handlers = {
 *     "storage" = "Drupal\yabrm\BibliographicContributorArchivalStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\yabrm\BibliographicContributorArchivalListBuilder",
 *     "views_data" = "Drupal\yabrm\Entity\BibliographicContributorArchivalViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\yabrm\Form\BibliographicContributorArchivalForm",
 *       "add" = "Drupal\yabrm\Form\BibliographicContributorArchivalForm",
 *       "edit" = "Drupal\yabrm\Form\BibliographicContributorArchivalForm",
 *       "delete" = "Drupal\yabrm\Form\BibliographicContributorArchivalDeleteForm",
 *     },
 *     "access" = "Drupal\yabrm\BibliographicContributorArchivalAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\yabrm\BibliographicContributorArchivalHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "yabrm_contributor_archival",
 *   revision_table = "yabrm_contributor_archival_revision",
 *   revision_data_table = "yabrm_contributor_archival_field_revision",
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log",
 *   },
 *   admin_permission = "administer bibliographic contributor archival entities",
 *   show_revision_ui = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/yabrm/yabrm_contributor_archival/{yabrm_contributor_archival}",
 *     "add-form" = "/yabrm/yabrm_contributor_archival/add",
 *     "edit-form" = "/yabrm/yabrm_contributor_archival/{yabrm_contributor_archival}/edit",
 *     "delete-form" = "/yabrm/yabrm_contributor_archival/{yabrm_contributor_archival}/delete",
 *     "version-history" = "/yabrm/yabrm_contributor_archival/{yabrm_contributor_archival}/revisions",
 *     "revision" = "/yabrm/yabrm_contributor_archival/{yabrm_contributor_archival}/revisions/{yabrm_contributor_archival_revision}/view",
 *     "revision_revert" = "/yabrm/yabrm_contributor_archival/{yabrm_contributor_archival}/revisions/{yabrm_contributor_archival_revision}/revert",
 *     "revision_delete" = "/yabrm/yabrm_contributor_archival/{yabrm_contributor_archival}/revisions/{yabrm_contributor_archival_revision}/delete",
 *     "collection" = "/yabrm/yabrm_contributor_archival",
 *   },
 *   field_ui_base_route = "yabrm_contributor_archival.settings"
 * )
 */
class BibliographicContributorArchival extends RevisionableContentEntityBase implements BibliographicContributorArchivalInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the yabrm_contributor_archival
    // owner the revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getWebsiteCatalogue() {
    return $this->get('website_catalogue')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setWebsiteCatalogue($website_catalogue) {
    $this->set('website_catalogue', $website_catalogue);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getInstitution() {
    return $this->get('institution')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setInstitution($institution) {
    $this->set('institution', $institution);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRetrievalNumber() {
    return $this->get('retrieval_number')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRetrievalNumber($retrieval_number) {
    $this->set('retrieval_number', $retrieval_number);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDateRange() {
    return $this->get('date_range')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDateRange($date_range) {
    $this->set('date_range', $date_range);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getExtent() {
    return $this->get('extent')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setExtent($extent) {
    $this->set('extent', $extent);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getScopeContent() {
    return $this->get('scope_content')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setScopeContent($scope_content) {
    $this->set('scope_content', $scope_content);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Bibliographic Contributor Archival entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The title of the Bibliographic Contributor Archival entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 256,
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

    $fields['website_catalogue'] = BaseFieldDefinition::create('link')
      ->setLabel(t('URL'))
      ->setRevisionable(TRUE)
      ->setDescription(t("A link to the Contributor's website/catalogue."))
      ->setDisplayOptions('form', [
        'type' => 'link',
        'weight' => -3,
      ])
      ->setSettings([
        'default_value' => '',
        'max_length' => 4096,
        'link_type' => LinkItemInterface::LINK_GENERIC,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['institution'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Institution/Department/Unit'))
      ->setDescription(t('The institution/department/unit for the Contributor Archival entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 256,
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

    $fields['retrieval_number'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Retrieval Number'))
      ->setDescription(t('The retrieval number for the Bibliographic Contributor Archival entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 256,
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
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(FALSE);

    $fields['date_range'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Date Range'))
      ->setDescription(t('The date range of the Bibliographic Contributor Archival entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 256,
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
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(FALSE);

    $fields['extent'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Extent'))
      ->setDescription(t('The extent of the Bibliographic Contributor Archival entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 256,
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
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(FALSE);

    $fields['scope_content'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Scope and Content'))
      ->setDescription(t('Scope and content of the Bibliographic Contributor Archival entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'default_value' => '',
        'max_length' => 2048,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_textarea',
        'weight' => -3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'text_processing' => 0,
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
