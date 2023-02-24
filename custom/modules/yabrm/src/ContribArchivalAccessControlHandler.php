<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Contrib Archival entity.
 *
 * @see \Drupal\yabrm\Entity\ContribArchival.
 */
class ContribArchivalAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\yabrm\Entity\ContribArchivalInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished contrib archival entities');
        }

        return AccessResult::allowedIfHasPermission($account, 'view published contrib archival entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit contrib archival entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete contrib archival entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add contrib archival entities');
  }

}
