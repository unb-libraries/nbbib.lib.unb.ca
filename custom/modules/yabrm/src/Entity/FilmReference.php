<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\yabrm\Entity\BibliographicReference;

/**
 * Defines the Film reference entity.
 *
 * @ingroup yabrm
 *
 * @ContentEntityType(
 *   id = "yabrm_film",
 *   label = @Translation("Film reference"),
 *   handlers = {
 *     "storage" = "Drupal\yabrm\FilmReferenceStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\yabrm\FilmReferenceListBuilder",
 *     "views_data" = "Drupal\yabrm\Entity\FilmReferenceViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\yabrm\Form\FilmReferenceForm",
 *       "add" = "Drupal\yabrm\Form\FilmReferenceForm",
 *       "edit" = "Drupal\yabrm\Form\FilmReferenceForm",
 *       "delete" = "Drupal\yabrm\Form\FilmReferenceDeleteForm",
 *     },
 *     "access" = "Drupal\yabrm\FilmReferenceAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\yabrm\FilmReferenceHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "yabrm_film",
 *   revision_table = "yabrm_film_revision",
 *   revision_data_table = "yabrm_film_field_revision",
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log",
 *   },
 *   admin_permission = "administer film reference entities",
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
 *     "canonical" = "/yabrm/yabrm_film/{yabrm_film}",
 *     "add-form" = "/yabrm/yabrm_film/add",
 *     "edit-form" = "/yabrm/yabrm_film/{yabrm_film}/edit",
 *     "delete-form" = "/yabrm/yabrm_film/{yabrm_film}/delete",
 *     "version-history" = "/yabrm/yabrm_film/{yabrm_film}/revisions",
 *     "revision" = "/yabrm/yabrm_film/{yabrm_film}/revisions/{yabrm_film_revision}/view",
 *     "revision_revert" = "/yabrm/yabrm_film/{yabrm_film}/revisions/{yabrm_film_revision}/revert",
 *     "revision_delete" = "/yabrm/yabrm_film/{yabrm_film}/revisions/{yabrm_film_revision}/delete",
 *     "collection" = "/yabrm/yabrm_film",
 *   },
 *   field_ui_base_route = "yabrm_film.settings"
 * )
 */
class FilmReference extends BibliographicReference implements FilmReferenceInterface {

  /**
   * {@inheritdoc}
   */
  public function getRunningTime() {
    return $this->get('running_time')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRunningTime($running_time) {
    $this->set('running_time', $running_time);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPerformers() {
    return $this->get('performers')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPerformers($performers) {
    $this->set('performers', $performers);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getGenre() {
    return $this->get('genre')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setGenre($genre) {
    $this->set('genre', $genre);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFilmFormat() {
    return $this->get('film_format')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setFilmFormat($film_format) {
    $this->set('film_format', $film_format);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getReleased() {
    return $this->get('released')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setReleased($released) {
    $this->set('released', $released);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getDistributor() {
    return $this->get('distributor')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDistributor($distributor) {
    $this->set('distributor', $distributor);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['distributor'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Distributor'))
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

    $fields['released'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Originally Released'))
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

    $fields['genre'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Genre'))
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

    $fields['film_format'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Film Format'))
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

    $fields['running_time'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Running Time'))
      ->setDescription(t('Film running time.'))
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

    $fields['performers'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Performers'))
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
