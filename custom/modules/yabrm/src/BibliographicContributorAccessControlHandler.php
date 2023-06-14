<?php

namespace Drupal\yabrm;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for the Bibliographic Contributor entity.
 *
 * @see \Drupal\yabrm\Entity\BibliographicContributor.
 */
class BibliographicContributorAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\yabrm\Entity\BibliographicContributorInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished bibliographic contributor entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published bibliographic contributor entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit bibliographic contributor entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete bibliographic contributor entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add bibliographic contributor entities');
  }

}
