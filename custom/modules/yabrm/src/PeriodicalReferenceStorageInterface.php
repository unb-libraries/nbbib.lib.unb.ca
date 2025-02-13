<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\yabrm\Entity\PeriodicalReferenceInterface;

/**
 * Defines the storage handler class for Periodical reference entities.
 *
 * This extends the base storage class, adding required special handling for
 * Periodical reference entities.
 *
 * @ingroup yabrm
 */
interface PeriodicalReferenceStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Periodical reference revision IDs for a specific Periodical reference.
   *
   * @param \Drupal\yabrm\Entity\PeriodicalReferenceInterface $entity
   *   The Periodical reference entity.
   *
   * @return int[]
   *   Periodical reference revision IDs (in ascending order).
   */
  public function revisionIds(PeriodicalReferenceInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Periodical reference author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Periodical reference revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\yabrm\Entity\PeriodicalReferenceInterface $entity
   *   The Periodical reference entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(PeriodicalReferenceInterface $entity);

  /**
   * Unsets the language for all Periodical reference with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
