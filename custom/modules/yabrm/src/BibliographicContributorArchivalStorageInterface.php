<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\yabrm\Entity\BibliographicContributorArchivalInterface;

/**
 * Defines the storage handler class for Bibliographic Contributor Archival entities.
 *
 * This extends the base storage class, adding required special handling for
 * Bibliographic Contributor Archival entities.
 *
 * @ingroup yabrm
 */
interface BibliographicContributorArchivalStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Bibliographic Contributor Archival revision IDs for a specific Bibliographic Contributor Archival.
   *
   * @param \Drupal\yabrm\Entity\BibliographicContributorArchivalInterface $entity
   *   The Bibliographic Contributor Archival entity.
   *
   * @return int[]
   *   Bibliographic Contributor Archival revision IDs (in ascending order).
   */
  public function revisionIds(BibliographicContributorArchivalInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Bibliographic Contributor Archival author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Bibliographic Contributor Archival revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\yabrm\Entity\BibliographicContributorArchivalInterface $entity
   *   The Bibliographic Contributor Archival entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(BibliographicContributorArchivalInterface $entity);

  /**
   * Unsets the language for all Bibliographic Contributor Archival with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
