<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\yabrm\Entity\FilmReferenceInterface;

/**
 * Defines the storage handler class for Film reference entities.
 *
 * This extends the base storage class, adding required special handling for
 * Film reference entities.
 *
 * @ingroup yabrm
 */
interface FilmReferenceStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Film reference revision IDs for a specific Film reference.
   *
   * @param \Drupal\yabrm\Entity\FilmReferenceInterface $entity
   *   The Film reference entity.
   *
   * @return int[]
   *   Film reference revision IDs (in ascending order).
   */
  public function revisionIds(FilmReferenceInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Film reference author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Film reference revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\yabrm\Entity\FilmReferenceInterface $entity
   *   The Film reference entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(FilmReferenceInterface $entity);

  /**
   * Unsets the language for all Film reference with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
