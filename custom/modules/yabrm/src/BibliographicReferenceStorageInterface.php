<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\yabrm\Entity\BibliographicReferenceInterface;

/**
 * Defines the storage handler class for Bibliographic Reference entities.
 *
 * This extends the base storage class, adding required special handling for
 * Bibliographic Reference entities.
 *
 * @ingroup yabrm
 */
interface BibliographicReferenceStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Bibliographic Reference revision IDs for a specific Bibliographic Reference.
   *
   * @param \Drupal\yabrm\Entity\BibliographicReferenceInterface $entity
   *   The Bibliographic Reference entity.
   *
   * @return int[]
   *   Bibliographic Reference revision IDs (in ascending order).
   */
  public function revisionIds(BibliographicReferenceInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Bibliographic Reference author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Bibliographic Reference revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\yabrm\Entity\BibliographicReferenceInterface $entity
   *   The Bibliographic Reference entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(BibliographicReferenceInterface $entity);

  /**
   * Unsets the language for all Bibliographic Reference with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
