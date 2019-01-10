<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\yabrm\Entity\BibliographicReference;

/**
 * Defines the Journal Article Reference entity.
 *
 * @ingroup yabrm
 *
 * @ContentEntityType(
 *   id = "yabrm_journal_article",
 *   label = @Translation("Journal Article Reference"),
 *   handlers = {
 *     "storage" = "Drupal\yabrm\JournalArticleReferenceStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\yabrm\JournalArticleReferenceListBuilder",
 *     "views_data" = "Drupal\yabrm\Entity\JournalArticleReferenceViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\yabrm\Form\JournalArticleReferenceForm",
 *       "add" = "Drupal\yabrm\Form\JournalArticleReferenceForm",
 *       "edit" = "Drupal\yabrm\Form\JournalArticleReferenceForm",
 *       "delete" = "Drupal\yabrm\Form\JournalArticleReferenceDeleteForm",
 *     },
 *     "access" = "Drupal\yabrm\JournalArticleReferenceAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\yabrm\JournalArticleReferenceHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "yabrm_journal_article",
 *   revision_table = "yabrm_journal_article_revision",
 *   revision_data_table = "yabrm_journal_article_field_revision",
 *   admin_permission = "administer journal article reference entities",
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
 *     "canonical" = "/yabrm/yabrm_journal_article/{yabrm_journal_article}",
 *     "add-form" = "/yabrm/yabrm_journal_article/add",
 *     "edit-form" = "/yabrm/yabrm_journal_article/{yabrm_journal_article}/edit",
 *     "delete-form" = "/yabrm/yabrm_journal_article/{yabrm_journal_article}/delete",
 *     "version-history" = "/yabrm/yabrm_journal_article/{yabrm_journal_article}/revisions",
 *     "revision" = "/yabrm/yabrm_journal_article/{yabrm_journal_article}/revisions/{yabrm_journal_article_revision}/view",
 *     "revision_revert" = "/yabrm/yabrm_journal_article/{yabrm_journal_article}/revisions/{yabrm_journal_article_revision}/revert",
 *     "revision_delete" = "/yabrm/yabrm_journal_article/{yabrm_journal_article}/revisions/{yabrm_journal_article_revision}/delete",
 *     "collection" = "/yabrm/yabrm_journal_article",
 *   },
 *   field_ui_base_route = "yabrm_journal_article.settings"
 * )
 */
class JournalArticleReference extends BibliographicReference implements JournalArticleReferenceInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['issn'] = BaseFieldDefinition::create('string')
      ->setLabel(t('ISSN'))
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

    $fields['doi'] = BaseFieldDefinition::create('string')
      ->setLabel(t('DOI'))
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

    $fields['issue'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Issue'))
      ->setSettings([
        'max_length' => 64,
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
        'max_length' => 64,
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

    $fields['journal_abbr'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Journal Abbreviation'))
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

    $fields['series_text'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Series Text'))
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

    $fields['series_text'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Series Text'))
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

    $fields['series_title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Series Title'))
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

    return $fields;
  }

}
