<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\yabrm\Entity\ContribArchivalInterface;

/**
 * Defines the storage handler class for Contrib Archival entities.
 *
 * This extends the base storage class, adding required special handling for
 * Contrib Archival entities.
 *
 * @ingroup yabrm
 */
interface ContribArchivalStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Contrib Archival revision IDs for a specific Contrib Archival.
   *
   * @param \Drupal\yabrm\Entity\ContribArchivalInterface $entity
   *   The Contrib Archival entity.
   *
   * @return int[]
   *   Contrib Archival revision IDs (in ascending order).
   */
  public function revisionIds(ContribArchivalInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Contrib Archival author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Contrib Archival revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\yabrm\Entity\ContribArchivalInterface $entity
   *   The Contrib Archival entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(ContribArchivalInterface $entity);

  /**
   * Unsets the language for all Contrib Archival with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
