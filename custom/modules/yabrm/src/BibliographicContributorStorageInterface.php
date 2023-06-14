<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\yabrm\Entity\BibliographicContributorInterface;

/**
 * Defines the storage handler class for Bibliographic Contributor entities.
 *
 * This extends the base storage class, adding required special handling for
 * Bibliographic Contributor entities.
 *
 * @ingroup yabrm
 */
interface BibliographicContributorStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Bibliographic Contributor revision IDs for a specific Bibliographic Contributor.
   *
   * @param \Drupal\yabrm\Entity\BibliographicContributorInterface $entity
   *   The Bibliographic Contributor entity.
   *
   * @return int[]
   *   Bibliographic Contributor revision IDs (in ascending order).
   */
  public function revisionIds(BibliographicContributorInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Bibliographic Contributor author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Bibliographic Contributor revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\yabrm\Entity\BibliographicContributorInterface $entity
   *   The Bibliographic Contributor entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(BibliographicContributorInterface $entity);

  /**
   * Unsets the language for all Bibliographic Contributor with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
