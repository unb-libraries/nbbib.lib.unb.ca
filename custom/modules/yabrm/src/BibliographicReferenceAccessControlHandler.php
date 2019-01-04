<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Bibliographic Reference entity.
 *
 * @see \Drupal\yabrm\Entity\BibliographicReference.
 */
class BibliographicReferenceAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\yabrm\Entity\BibliographicReferenceInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished bibliographic reference entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published bibliographic reference entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit bibliographic reference entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete bibliographic reference entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add bibliographic reference entities');
  }

}
