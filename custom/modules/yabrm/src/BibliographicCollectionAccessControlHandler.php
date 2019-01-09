<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Bibliographic Collection entity.
 *
 * @see \Drupal\yabrm\Entity\BibliographicCollection.
 */
class BibliographicCollectionAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\yabrm\Entity\BibliographicCollectionInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished bibliographic collection entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published bibliographic collection entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit bibliographic collection entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete bibliographic collection entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add bibliographic collection entities');
  }

}
