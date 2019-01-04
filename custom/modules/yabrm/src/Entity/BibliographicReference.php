<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Bibliographic Reference entity.
 *
 * @ingroup yabrm
 *
 * @ContentEntityType(
 *   id = "yabrm_biblio_reference",
 *   label = @Translation("Bibliographic Reference"),
 *   handlers = {
 *     "storage" = "Drupal\yabrm\BibliographicReferenceStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\yabrm\BibliographicReferenceListBuilder",
 *     "views_data" = "Drupal\yabrm\Entity\BibliographicReferenceViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\yabrm\Form\BibliographicReferenceForm",
 *       "add" = "Drupal\yabrm\Form\BibliographicReferenceForm",
 *       "edit" = "Drupal\yabrm\Form\BibliographicReferenceForm",
 *       "delete" = "Drupal\yabrm\Form\BibliographicReferenceDeleteForm",
 *     },
 *     "access" = "Drupal\yabrm\BibliographicReferenceAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\yabrm\BibliographicReferenceHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "yabrm_biblio_reference",
 *   revision_table = "yabrm_biblio_reference_revision",
 *   revision_data_table = "yabrm_biblio_reference_field_revision",
 *   admin_permission = "administer bibliographic reference entities",
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
 *     "canonical" = "/yabrm/yabrm_biblio_reference/{yabrm_biblio_reference}",
 *     "add-form" = "/yabrm/yabrm_biblio_reference/add",
 *     "edit-form" = "/yabrm/yabrm_biblio_reference/{yabrm_biblio_reference}/edit",
 *     "delete-form" = "/yabrm/yabrm_biblio_reference/{yabrm_biblio_reference}/delete",
 *     "version-history" = "/yabrm/yabrm_biblio_reference/{yabrm_biblio_reference}/revisions",
 *     "revision" = "/yabrm/yabrm_biblio_reference/{yabrm_biblio_reference}/revisions/{yabrm_biblio_reference_revision}/view",
 *     "revision_revert" = "/yabrm/yabrm_biblio_reference/{yabrm_biblio_reference}/revisions/{yabrm_biblio_reference_revision}/revert",
 *     "revision_delete" = "/yabrm/yabrm_biblio_reference/{yabrm_biblio_reference}/revisions/{yabrm_biblio_reference_revision}/delete",
 *     "collection" = "/yabrm/yabrm_biblio_reference",
 *   },
 *   field_ui_base_route = "yabrm_biblio_reference.settings"
 * )
 */
class BibliographicReference extends RevisionableContentEntityBase implements BibliographicReferenceInterface {

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

    // If no revision author has been set explicitly, make the yabrm_biblio_reference owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
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
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Bibliographic Reference entity.'))
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
      ->setSettings([
        'max_length' => 1024,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setRequired(TRUE)
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

    $fields['short_title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Short Title'))
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

    $fields['external_key_ref'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Key'))
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

    $fields['publication_year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Publication Year'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 4,
      ])
      ->setDisplayOptions('form', [
        'weight' => 4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['publication_month'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Publication Month'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 4,
      ])
      ->setDisplayOptions('form', [
        'weight' => 4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['publication_day'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Publication Day'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'weight' => 4,
      ])
      ->setDisplayOptions('form', [
        'weight' => 4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['contributors'] = BaseFieldDefinition::create('entity_reference_revisions')
      ->setLabel(t('Contributor(s)'))
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
      ->setSettings(
        [
          'target_type' => 'paragraph',
          'handler' => 'default:paragraph',
          'handler_settings' => [
            'negate' => 0,
            'target_bundles' => [
              'yabrm_bibliographic_contributor' => 'yabrm_bibliographic_contributor',
            ],
            'target_bundles_drag_drop' => [
              'yabrm_bibliographic_contributor' => [
                'enabled' => TRUE,
                'weight' => 2,
              ],
            ],
          ],
        ]
      )
      ->setDisplayOptions(
        'view',
        [
          'label' => 'above',
          'type' => 'number',
          'weight' => -1,
        ]
      )
      ->setDisplayOptions('form', [
        'type' => 'paragraphs',
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['url'] = BaseFieldDefinition::create('uri')
      ->setLabel(t('URL'))
      ->setDescription(t('The fully-qualified URL of the feed.'))
      ->setDisplayOptions('form', [
        'type' => 'uri',
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['abstract_note'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Abstract Note'))
      ->setDescription(t('Abstract Note.'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 2048,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_long',
        'weight' => -3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'text_long',
        'text_processing' => 0,
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['language'] = BaseFieldDefinition::create('list_string')
      ->setSettings([
        'allowed_values' => ['eng' => 'English', 'fre' => 'French'],
      ])
      ->setLabel('Language')
      ->setRequired(TRUE)
      ->setDescription('Select the language of this reference')
      ->setDisplayOptions('form', [
        'type' => 'options_buttons',
        'weight' => 2,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_long',
        'weight' => -3,
      ])
      ->setDefaultValue([['value' => 'eng']])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['rights'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Rights'))
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

    $fields['archive'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Archive'))
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

    $fields['archive_location'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Archive Location'))
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

    $fields['library_catalog'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Library Catalog'))
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

    $fields['call_number'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Call Number'))
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

    $fields['extra'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Extra'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 2048,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_long',
        'weight' => -3,
      ])
      ->setDisplayOptions('form', [
        'type' => 'text_long',
        'text_processing' => 0,
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('Published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
