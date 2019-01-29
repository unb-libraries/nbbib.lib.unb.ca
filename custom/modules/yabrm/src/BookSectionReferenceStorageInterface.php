<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\yabrm\Entity\BookSectionReferenceInterface;

/**
 * Defines the storage handler class for Book section reference entities.
 *
 * This extends the base storage class, adding required special handling for
 * Book section reference entities.
 *
 * @ingroup yabrm
 */
interface BookSectionReferenceStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Book section reference revision IDs for a specific Book section reference.
   *
   * @param \Drupal\yabrm\Entity\BookSectionReferenceInterface $entity
   *   The Book section reference entity.
   *
   * @return int[]
   *   Book section reference revision IDs (in ascending order).
   */
  public function revisionIds(BookSectionReferenceInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Book section reference author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Book section reference revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\yabrm\Entity\BookSectionReferenceInterface $entity
   *   The Book section reference entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(BookSectionReferenceInterface $entity);

  /**
   * Unsets the language for all Book section reference with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
