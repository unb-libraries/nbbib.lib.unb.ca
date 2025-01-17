<?php

namespace Drupal\yabrm;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for the Conference reference entity.
 *
 * @see \Drupal\yabrm\Entity\ConferenceReference.
 */
class ConferenceReferenceAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\yabrm\Entity\ConferenceReferenceInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished conference reference entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published conference reference entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit conference reference entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete conference reference entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add conference reference entities');
  }

}
