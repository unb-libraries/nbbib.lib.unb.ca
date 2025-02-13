<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Periodical reference entities.
 *
 * @ingroup yabrm
 */
class PeriodicalReferenceListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Periodical reference ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\yabrm\Entity\PeriodicalReference */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.yabrm_periodical.edit_form',
      ['yabrm_periodical' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
