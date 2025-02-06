<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\yabrm\Entity\ReportReferenceInterface;

/**
 * Defines the storage handler class for Report reference entities.
 *
 * This extends the base storage class, adding required special handling for
 * Report reference entities.
 *
 * @ingroup yabrm
 */
interface ReportReferenceStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Report reference revision IDs for a specific Report reference.
   *
   * @param \Drupal\yabrm\Entity\ReportReferenceInterface $entity
   *   The Report reference entity.
   *
   * @return int[]
   *   Report reference revision IDs (in ascending order).
   */
  public function revisionIds(ReportReferenceInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Report reference author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Report reference revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\yabrm\Entity\ReportReferenceInterface $entity
   *   The Report reference entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(ReportReferenceInterface $entity);

  /**
   * Unsets the language for all Report reference with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
