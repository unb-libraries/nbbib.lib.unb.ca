<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\yabrm\Entity\BibliographicCollectionInterface;

/**
 * Defines the storage handler class for Bibliographic Collection entities.
 *
 * This extends the base storage class, adding required special handling for
 * Bibliographic Collection entities.
 *
 * @ingroup yabrm
 */
interface BibliographicCollectionStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Bibliographic Collection revision IDs for a specific Bibliographic Collection.
   *
   * @param \Drupal\yabrm\Entity\BibliographicCollectionInterface $entity
   *   The Bibliographic Collection entity.
   *
   * @return int[]
   *   Bibliographic Collection revision IDs (in ascending order).
   */
  public function revisionIds(BibliographicCollectionInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Bibliographic Collection author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Bibliographic Collection revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\yabrm\Entity\BibliographicCollectionInterface $entity
   *   The Bibliographic Collection entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(BibliographicCollectionInterface $entity);

  /**
   * Unsets the language for all Bibliographic Collection with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
