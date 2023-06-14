<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\yabrm\Entity\ThesisReferenceInterface;

/**
 * Defines the storage handler class for Thesis reference entities.
 *
 * This extends the base storage class, adding required special handling for
 * Thesis reference entities.
 *
 * @ingroup yabrm
 */
interface ThesisReferenceStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Thesis reference revision IDs for a specific Thesis reference.
   *
   * @param \Drupal\yabrm\Entity\ThesisReferenceInterface $entity
   *   The Thesis reference entity.
   *
   * @return int[]
   *   Thesis reference revision IDs (in ascending order).
   */
  public function revisionIds(ThesisReferenceInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Thesis reference author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Thesis reference revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\yabrm\Entity\ThesisReferenceInterface $entity
   *   The Thesis reference entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(ThesisReferenceInterface $entity);

  /**
   * Unsets the language for all Thesis reference with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
