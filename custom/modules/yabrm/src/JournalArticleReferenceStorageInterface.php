<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\yabrm\Entity\JournalArticleReferenceInterface;

/**
 * Defines the storage handler class for Journal Article Reference entities.
 *
 * This extends the base storage class, adding required special handling for
 * Journal Article Reference entities.
 *
 * @ingroup yabrm
 */
interface JournalArticleReferenceStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Journal Article Reference revision IDs for a specific Journal Article Reference.
   *
   * @param \Drupal\yabrm\Entity\JournalArticleReferenceInterface $entity
   *   The Journal Article Reference entity.
   *
   * @return int[]
   *   Journal Article Reference revision IDs (in ascending order).
   */
  public function revisionIds(JournalArticleReferenceInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Journal Article Reference author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Journal Article Reference revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\yabrm\Entity\JournalArticleReferenceInterface $entity
   *   The Journal Article Reference entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(JournalArticleReferenceInterface $entity);

  /**
   * Unsets the language for all Journal Article Reference with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
