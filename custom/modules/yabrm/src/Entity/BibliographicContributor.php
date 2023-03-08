<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Link\LinkItemInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Bibliographic Contributor entity.
 *
 * @ingroup yabrm
 *
 * @ContentEntityType(
 *   id = "yabrm_contributor",
 *   label = @Translation("Bibliographic Contributor"),
 *   handlers = {
 *     "storage" = "Drupal\yabrm\BibliographicContributorStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\yabrm\BibliographicContributorListBuilder",
 *     "views_data" = "Drupal\yabrm\Entity\BibliographicContributorViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\yabrm\Form\BibliographicContributorForm",
 *       "add" = "Drupal\yabrm\Form\BibliographicContributorForm",
 *       "edit" = "Drupal\yabrm\Form\BibliographicContributorForm",
 *       "delete" = "Drupal\yabrm\Form\BibliographicContributorDeleteForm",
 *     },
 *     "access" = "Drupal\yabrm\BibliographicContributorAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\yabrm\BibliographicContributorHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "yabrm_contributor",
 *   revision_table = "yabrm_contributor_revision",
 *   revision_data_table = "yabrm_contributor_field_revision",
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log",
 *   },
 *   admin_permission = "administer bibliographic contributor entities",
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
 *     "canonical" = "/yabrm/yabrm_contributor/{yabrm_contributor}",
 *     "add-form" = "/yabrm/yabrm_contributor/add",
 *     "edit-form" = "/yabrm/yabrm_contributor/{yabrm_contributor}/edit",
 *     "delete-form" = "/yabrm/yabrm_contributor/{yabrm_contributor}/delete",
 *     "version-history" = "/yabrm/yabrm_contributor/{yabrm_contributor}/revisions",
 *     "revision" = "/yabrm/yabrm_contributor/{yabrm_contributor}/revisions/{yabrm_contributor_revision}/view",
 *     "revision_revert" = "/yabrm/yabrm_contributor/{yabrm_contributor}/revisions/{yabrm_contributor_revision}/revert",
 *     "revision_delete" = "/yabrm/yabrm_contributor/{yabrm_contributor}/revisions/{yabrm_contributor_revision}/delete",
 *     "collection" = "/yabrm/yabrm_contributor",
 *   },
 *   field_ui_base_route = "yabrm_contributor.settings"
 * )
 */
class BibliographicContributor extends RevisionableContentEntityBase implements BibliographicContributorInterface {

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

    // If no revision author has been set explicitly, make the yabrm_contributor
    // owner the revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getZoteroName() {
    return $this->get('zotero_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setZoteroName($zotero_name) {
    $this->set('zotero_name', $zotero_name);
    return $this;
  }

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
  public function getFirstName() {
    return $this->get('first_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFirstName($first_name) {
    $this->set('first_name', $first_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLastName() {
    return $this->get('last_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setLastName($last_name) {
    $this->set('last_name', $last_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getInstitutionName() {
    return $this->get('institution_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setInstitutionName($institution_name) {
    $this->set('institution_name', $institution_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSortName() {
    return $this->get('sort_name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSortName($sort_name) {
    $this->set('sort_name', $sort_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPrefix() {
    return $this->get('prefix')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPrefix($prefix) {
    $this->set('prefix', $prefix);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSuffix() {
    return $this->get('suffix')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSuffix($suffix) {
    $this->set('suffix', $suffix);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPicture() {
    return $this->get('picture')->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setPicture($values) {
    return $this->set('picture', $values);
  }

  /**
   * {@inheritdoc}
   */
  public function setPicCaption($pic_caption) {
    $this->set('pic_caption', $pic_caption);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPicCaption() {
    return $this->get('pic_caption')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->get('description')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDescription($description) {
    $this->set('description', $description);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getBirthYear() {
    return $this->get('birth_year')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setBirthYear($year) {
    $this->set('birth_year', $year);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDeathYear() {
    return $this->get('death_year')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDeathYear($year) {
    $this->set('death_year', $year);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getNbResidences() {
    return $this->get('nb_residences')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setNbResidences($nb_residences) {
    $this->set('nb_residences', $nb_residences);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getNbleUrl() {
    return $this->get('nble_url')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setNbleUrl($nble_url) {
    $this->set('nble_url', $nble_url);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getArchivalNote() {
    return $this->get('archival_note')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setArchivalNote($archival_note) {
    $this->set('archival_note', $archival_note);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getArchival() {
    return $this->get('archival')->referencedEntities();
  }

  /**
   * {@inheritdoc}
   */
  public function setArchival($archival) {
    $this->set('archival', $archival);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Bibliographic Contributor entity.'))
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

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Bibliographic Contributor entity.'))
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

    $fields['zotero_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Zotero Name'))
      ->setDescription(t('The imported name of the Bibliographic Contributor entity.'))
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

    $fields['first_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First Name'))
      ->setDescription(t('The first name of the Bibliographic Contributor entity.'))
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

    $fields['last_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Last Name'))
      ->setDescription(t('The last name of the Bibliographic Contributor entity.'))
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

    $fields['institution_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Institution Name'))
      ->setDescription(t('The institution name of the Bibliographic Contributor entity.'))
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

    $fields['sort_name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Sort Name'))
      ->setDescription(t('The sort name of the Bibliographic Contributor entity.'))
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

    $fields['prefix'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Prefix'))
      ->setDescription(t('e.g. Rev., Honorable but not Mr., Mrs, etc.'))
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

    $fields['suffix'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Suffix'))
      ->setDescription(t('e.g. Jr., Senior, O.F.M., etc.'))
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

    $fields['birth_year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Year of Birth'))
      ->setRevisionable(TRUE)
      ->addPropertyConstraints('value', [
        'Range' => [
          'min' => 800,
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

    $fields['death_year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Year of Death'))
      ->setRevisionable(TRUE)
      ->addPropertyConstraints('value', [
        'Range' => [
          'min' => 800,
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

    $fields['picture'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Picture'))
      ->setDescription(t('Picture of the contributor.'))
      ->setRequired(FALSE)
      ->setSettings([
        'file_extensions' => 'png jpg jpeg',
      ])
      ->setDisplayOptions(
        'view',
        [
          'label'   => 'above',
          'type'    => 'image',
          'weight'  => 0,
        ],
      )
      ->setDisplayOptions(
        'form',
        [
          'type'    => 'image_image',
          'weight'  => 0,
        ],
      )
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['pic_caption'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Picture Caption'))
      ->setDescription(t('Contributor picture caption.'))
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

    $fields['nb_residences'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Predominant New Brunswick Residences'))
      ->setRevisionable(TRUE)
      ->setSettings(
        [
          'target_type' => 'taxonomy_term',
          'handler' => 'default:taxonomy_term',
          'handler_settings' => [
            'target_bundles' => [
              'nbbib_residences' => 'nbbib_residences',
            ],
            'auto_create' => TRUE,
          ],
        ]
      )
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
      ->setRequired(FALSE)
      ->setDisplayOptions(
        'view',
        [
          'label' => 'above',
          'weight' => 0,
        ]
      )
      ->setDisplayOptions(
        'form',
        [
          'type' => 'entity_reference_autocomplete',
          'weight' => 0,
          'settings' => [
            'match_operator' => 'CONTAINS',
            'size' => '10',
            'autocomplete_type' => 'tags',
            'placeholder' => '',
          ],
        ]
      )
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDescription(t('Description of the Bibliographic Contributor entity.'))
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

    $fields['nble_url'] = BaseFieldDefinition::create('link')
      ->setLabel(t('NBLE URL'))
      ->setRevisionable(TRUE)
      ->setDescription(t('Fully-qualified NBLE URL for the contributor, eg. https://nble.lib.unb.ca/browse/g/william-francis-ganong'))
      ->setDisplayOptions('form', [
        'type' => 'link_default',
        'weight' => -3,
      ])
      ->setSettings([
        'default_value' => '',
        'max_length' => 4096,
        'link_type' => LinkItemInterface::LINK_EXTERNAL,
        'title' => DRUPAL_DISABLED,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['archival_note'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('General Archival Note'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'default_value' => '',
        'max_length' => 2048,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_default',
        'weight' => -3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'text_processing' => 0,
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['archival'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Archival Record(s)'))
      ->setRevisionable(TRUE)
      ->setSettings(
        [
          'target_type' => 'yabrm_contrib_archival',
          'handler' => 'default',
        ]
      )
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
      ->setRequired(FALSE)
      ->setDisplayOptions(
        'view',
        [
          'label' => 'above',
          'weight' => 0,
        ]
      )
      ->setDisplayOptions(
        'form',
        [
          'type' => 'entity_reference_autocomplete',
          'weight' => 0,
          'settings' => [
            'match_operator' => 'CONTAINS',
            'size' => '10',
            'autocomplete_type' => 'tags',
            'placeholder' => '',
          ],
        ]
      )
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Published'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 0,
      ],
      )
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
