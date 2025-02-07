<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\yabrm\Entity\BibliographicReference;

/**
 * Defines the Report reference entity.
 *
 * @ingroup yabrm
 *
 * @ContentEntityType(
 *   id = "yabrm_report",
 *   label = @Translation("Report reference"),
 *   handlers = {
 *     "storage" = "Drupal\yabrm\ReportReferenceStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\yabrm\ReportReferenceListBuilder",
 *     "views_data" = "Drupal\yabrm\Entity\ReportReferenceViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\yabrm\Form\ReportReferenceForm",
 *       "add" = "Drupal\yabrm\Form\ReportReferenceForm",
 *       "edit" = "Drupal\yabrm\Form\ReportReferenceForm",
 *       "delete" = "Drupal\yabrm\Form\ReportReferenceDeleteForm",
 *     },
 *     "access" = "Drupal\yabrm\ReportReferenceAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\yabrm\ReportReferenceHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "yabrm_report",
 *   revision_table = "yabrm_report_revision",
 *   revision_data_table = "yabrm_report_field_revision",
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log",
 *   },
 *   admin_permission = "administer report reference entities",
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
 *     "canonical" = "/yabrm/yabrm_report/{yabrm_report}",
 *     "add-form" = "/yabrm/yabrm_report/add",
 *     "edit-form" = "/yabrm/yabrm_report/{yabrm_report}/edit",
 *     "delete-form" = "/yabrm/yabrm_report/{yabrm_report}/delete",
 *     "version-history" = "/yabrm/yabrm_report/{yabrm_report}/revisions",
 *     "revision" = "/yabrm/yabrm_report/{yabrm_report}/revisions/{yabrm_report_revision}/view",
 *     "revision_revert" = "/yabrm/yabrm_report/{yabrm_report}/revisions/{yabrm_report_revision}/revert",
 *     "revision_delete" = "/yabrm/yabrm_report/{yabrm_report}/revisions/{yabrm_report_revision}/delete",
 *     "collection" = "/yabrm/yabrm_report",
 *   },
 *   field_ui_base_route = "yabrm_report.settings"
 * )
 */
class ReportReference extends BibliographicReference implements ReportReferenceInterface {

  /**
   * {@inheritdoc}
   */
  public function getReportNumber() {
    return $this->get('report_number')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setReportNumber($report_number) {
    $this->set('report_number', $report_number);
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
  public function getSeries() {
    return $this->get('series')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSeries($series) {
    $this->set('series', $series);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getReportType() {
    return $this->get('report_type')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setReportType($report_type) {
    $this->set('report_type', $report_type);
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
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['institution'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Institution'))
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

    $fields['num_pages'] = BaseFieldDefinition::create('string')
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

    $fields['series'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Series'))
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

    $fields['report_type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Report Type'))
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

    $fields['report_number'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Report Number'))
      ->setDescription(t('Report number for the item.'))
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
