<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Journal Article Reference entity.
 *
 * @see \Drupal\yabrm\Entity\JournalArticleReference.
 */
class JournalArticleReferenceAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\yabrm\Entity\JournalArticleReferenceInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished journal article reference entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published journal article reference entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit journal article reference entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete journal article reference entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add journal article reference entities');
  }

}
