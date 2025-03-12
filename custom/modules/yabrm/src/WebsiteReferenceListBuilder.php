<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of website reference entities.
 *
 * @ingroup yabrm
 */
class WebsiteReferenceListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('website reference ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\yabrm\Entity\WebsiteReference */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.yabrm_website.edit_form',
      ['yabrm_website' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
