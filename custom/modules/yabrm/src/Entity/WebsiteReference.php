<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\yabrm\Entity\BibliographicReference;

/**
 * Defines the Website reference entity.
 *
 * @ingroup yabrm
 *
 * @ContentEntityType(
 *   id = "yabrm_website",
 *   label = @Translation("Website reference"),
 *   handlers = {
 *     "storage" = "Drupal\yabrm\WebsiteReferenceStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\yabrm\WebsiteReferenceListBuilder",
 *     "views_data" = "Drupal\yabrm\Entity\WebsiteReferenceViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\yabrm\Form\WebsiteReferenceForm",
 *       "add" = "Drupal\yabrm\Form\WebsiteReferenceForm",
 *       "edit" = "Drupal\yabrm\Form\WebsiteReferenceForm",
 *       "delete" = "Drupal\yabrm\Form\WebsiteReferenceDeleteForm",
 *     },
 *     "access" = "Drupal\yabrm\WebsiteReferenceAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\yabrm\WebsiteReferenceHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "yabrm_website",
 *   revision_table = "yabrm_website_revision",
 *   revision_data_table = "yabrm_website_field_revision",
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log",
 *   },
 *   admin_permission = "administer website reference entities",
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
 *     "canonical" = "/yabrm/yabrm_website/{yabrm_website}",
 *     "add-form" = "/yabrm/yabrm_website/add",
 *     "edit-form" = "/yabrm/yabrm_website/{yabrm_website}/edit",
 *     "delete-form" = "/yabrm/yabrm_website/{yabrm_website}/delete",
 *     "version-history" = "/yabrm/yabrm_website/{yabrm_website}/revisions",
 *     "revision" = "/yabrm/yabrm_website/{yabrm_website}/revisions/{yabrm_website_revision}/view",
 *     "revision_revert" = "/yabrm/yabrm_website/{yabrm_website}/revisions/{yabrm_website_revision}/revert",
 *     "revision_delete" = "/yabrm/yabrm_website/{yabrm_website}/revisions/{yabrm_website_revision}/delete",
 *     "collection" = "/yabrm/yabrm_website",
 *   },
 *   field_ui_base_route = "yabrm_website.settings"
 * )
 */
class WebsiteReference extends BibliographicReference implements WebsiteReferenceInterface {

  /**
   * {@inheritdoc}
   */
  public function getSiteUrl() {
    return $this->get('site_url')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSiteUrl($site_url) {
    $this->set('place', $site_url);
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
  public function getRevised() {
    return $this->get('revised')->date;
  }

  /**
   * {@inheritdoc}
   */
  public function setRevised($last_revision) {
    if (is_string($last_revision)) {
      $last_revision = new \Drupal\Core\Datetime\DrupalDateTime($last_revision);
    }
    $this->set('revised', $last_revision);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['organization'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Organization'))
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

    $fields['site_url'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Site URL'))
      ->setDescription(t('URL for the item.'))
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

      $fields['revised'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Last Revision Date'))
      ->setRequired(TRUE)
      ->setSettings([
        'datetime_type' => 'datetime',
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'datetime_default',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'datetime_default',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
