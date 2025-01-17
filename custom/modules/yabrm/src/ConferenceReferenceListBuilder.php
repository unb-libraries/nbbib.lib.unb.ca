<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Conference reference entities.
 *
 * @ingroup yabrm
 */
class ConferenceReferenceListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Conference reference ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\yabrm\Entity\ConferenceReference */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.yabrm_conference.edit_form',
      ['yabrm_conference' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
