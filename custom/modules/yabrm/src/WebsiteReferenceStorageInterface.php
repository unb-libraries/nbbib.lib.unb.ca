<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\yabrm\Entity\WebsiteReferenceInterface;

/**
 * Defines the storage handler class for Website reference entities.
 *
 * This extends the base storage class, adding required special handling for
 * Website reference entities.
 *
 * @ingroup yabrm
 */
interface WebsiteReferenceStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Website reference revision IDs for a specific Website reference.
   *
   * @param \Drupal\yabrm\Entity\WebsiteReferenceInterface $entity
   *   The Website reference entity.
   *
   * @return int[]
   *   Website reference revision IDs (in ascending order).
   */
  public function revisionIds(WebsiteReferenceInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Website reference author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Website reference revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\yabrm\Entity\WebsiteReferenceInterface $entity
   *   The Website reference entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(WebsiteReferenceInterface $entity);

  /**
   * Unsets the language for all Website reference with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
