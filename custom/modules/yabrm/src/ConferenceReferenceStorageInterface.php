<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\yabrm\Entity\ConferenceReferenceInterface;

/**
 * Defines the storage handler class for Conference reference entities.
 *
 * This extends the base storage class, adding required special handling for
 * Conference reference entities.
 *
 * @ingroup yabrm
 */
interface ConferenceReferenceStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Conference reference revision IDs for a specific Conference reference.
   *
   * @param \Drupal\yabrm\Entity\ConferenceReferenceInterface $entity
   *   The Conference reference entity.
   *
   * @return int[]
   *   Conference reference revision IDs (in ascending order).
   */
  public function revisionIds(ConferenceReferenceInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Conference reference author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Conference reference revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\yabrm\Entity\ConferenceReferenceInterface $entity
   *   The Conference reference entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(ConferenceReferenceInterface $entity);

  /**
   * Unsets the language for all Conference reference with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
