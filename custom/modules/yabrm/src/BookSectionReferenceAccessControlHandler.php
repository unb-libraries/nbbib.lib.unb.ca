<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Book section reference entity.
 *
 * @see \Drupal\yabrm\Entity\BookSectionReference.
 */
class BookSectionReferenceAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\yabrm\Entity\BookSectionReferenceInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished book section reference entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published book section reference entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit book section reference entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete book section reference entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add book section reference entities');
  }

}
