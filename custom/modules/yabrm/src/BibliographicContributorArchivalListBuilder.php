<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Bibliographic Contributor Archival entities.
 *
 * @ingroup yabrm
 */
class BibliographicContributorArchivalListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Bibliographic Contributor Archival ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\yabrm\Entity\BibliographicContributorArchival */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.yabrm_contributor_archival.edit_form',
      ['yabrm_contributor_archival' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
