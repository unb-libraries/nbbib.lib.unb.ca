<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Thesis reference entities.
 *
 * @ingroup yabrm
 */
class ThesisReferenceListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Thesis reference ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\yabrm\Entity\ThesisReference */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.yabrm_thesis.edit_form',
      ['yabrm_thesis' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
