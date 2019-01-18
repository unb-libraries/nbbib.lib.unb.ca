<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\yabrm\Entity\BookReferenceInterface;

/**
 * Defines the storage handler class for Book reference entities.
 *
 * This extends the base storage class, adding required special handling for
 * Book reference entities.
 *
 * @ingroup yabrm
 */
interface BookReferenceStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Book reference revision IDs for a specific Book reference.
   *
   * @param \Drupal\yabrm\Entity\BookReferenceInterface $entity
   *   The Book reference entity.
   *
   * @return int[]
   *   Book reference revision IDs (in ascending order).
   */
  public function revisionIds(BookReferenceInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Book reference author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Book reference revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\yabrm\Entity\BookReferenceInterface $entity
   *   The Book reference entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(BookReferenceInterface $entity);

  /**
   * Unsets the language for all Book reference with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
