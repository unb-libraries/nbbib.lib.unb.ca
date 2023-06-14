<?php

namespace Drupal\yabrm;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for the Book reference entity.
 *
 * @see \Drupal\yabrm\Entity\BookReference.
 */
class BookReferenceAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\yabrm\Entity\BookReferenceInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished book reference entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published book reference entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit book reference entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete book reference entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add book reference entities');
  }

}
